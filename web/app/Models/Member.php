<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ImsMcMappingFans;
use App\Models\ImsMember;
use App\Models\Region;
use App\Models\Fans;
use App\Models\FansDetail;
class Member extends Model
{

    protected $table = 'member';
    public $allerganPublicId=7; //艾尔建公账号在微擎系统中的id=7
    // 查询数据
    public function getDayMemberInfo($day){
        $where = array();
        $intDate = strtotime($day.'00:00:00');
        $intEndDate = strtotime($day.'23:59:59');
        $res = $this->with(['memberIdJob:member_id,job','provinceInfo:id,name','cityInfo:id,name'])->select('member_id','name','sex','openid','mobile','birthday','province','city','create_time')->where('create_time','>=',$intDate)->where('create_time','<=',$intEndDate)->get()->toArray();

        return $res;
    }
    // 获取省份
    public function provinceInfo(){
        return $this->hasOne(Region::class, 'id', 'province');
    }

    // 获取市区
    public function cityInfo(){
        return $this->hasOne(Region::class, 'id', 'city');
    }

    // 关联查询job,member_id
    public function memberIdJob(){
        return $this->hasOne(MemberDetail::class, 'member_id', 'member_id');
    }

    // 关联查询job
    public function memberJob(){
    	return $this->hasOne(MemberDetail::class, 'openid', 'openid')->select('job');
    }
    // 根据条件更新数据
    public function updateInfoWhere($data=array(),$member_id){
    	$res = $this->where(['member_id'=>$member_id])->update($data);
        return $res;
    }

    // 数据更新
    public function paramInfoUpdate($res=array()){
    	# 实例化微擎数据库
        $weiqingModel = new ImsMcMappingFans();
        $weiqingImsMemberModel = new ImsMember();
        $allerganFans = new Fans();
        $fansDetailModel = new FansDetail();
        
        foreach ($res as $key => $value) {
          try{
            # 根据openid去查找微擎表ims_mc_mapping_fans，根据openid查找获得uid，
            # 1 如果 uid > 0,则根据uid去更新微擎ims_mc_members表其它信息，然后更新艾尔建表member字段isSyncWq为1
            # 3 其它情况,则不更新直接添加微擎会员，再update 微擎粉丝表，将uid更新成添加的会员id，并更新艾尔建表member字段isSyncWq为1
            $mapaa=array();
            $mapaa['uniacid']=$this->allerganPublicId;
            $mapaa['openid']=$value['openid'];
            $weiqingInfo = $weiqingModel->select('uid','tag','nickname')->with('memberInfo:uid')->where($mapaa)->first();
            $weiqingInfo = $weiqingInfo ? $weiqingInfo->toArray() : [];
            // 找到微擎的uid判断是否更新，新增,或者终止
            if(empty($weiqingInfo)){
                echo 'no mc_fans:'.$value['oepnid'];
                ///如果微擎表ims_mc_mapping_fans，根据openid找不到数据，先往ims_mc_members新增会员得到uid，然后往ImsMcMappingFans表新增数据，
                //acid=uniacid=7，uid来自会员表uid，nickname=艾尔建fans.nickname,groupid=6,
                //salt='aaaaaaaa',follow=艾尔建fans.subscribe,followtime=艾尔建fans.insert_datetime,
                //unfollowtime=0,tag=base64_encode(serialize(艾尔建fans_detail.对应数据)),
                //updatetime=fans_detail.最后更新时间,unionid=''

                // 查找艾尔建粉丝表
                $allerganFansInfo = $allerganFans->where(['openid'=>$value['openid']])->first();
                $allerganFansInfo = $allerganFansInfo?$allerganFansInfo->toArray() : [];

                // 查找艾尔建fans详情表
                $fansDetailInfo = $fansDetailModel->setFindInfo($value['openid']);
                $fansDetailInfo = $fansDetailInfo?$fansDetailInfo->toArray():[];
                // 新增到微擎会员表数据  
                $weiqingAry = $this->updateDataAry($value,$weiqingInfo,'add');
                $addRes = $weiqingImsMemberModel->addInfo($weiqingAry); // 得到微擎会员主键
                echo 'ImsMember add re:'.$addRes;
                // echo '||'.$weiqingImsMemberModel->getLastSql().'||';
                $fansDataAry = array();
                $fansDataAry['acid'] = $fansDataAry['uniacid'] = $this->allerganPublicId;
                $fansDataAry['openid'] = $value['openid'];
                $fansDataAry['uid'] = $addRes;
                $fansDataAry['nickname'] = !empty($allerganFansInfo)?($allerganFansInfo['nickname']?$allerganFansInfo['nickname']:''):'';
                $fansDataAry['groupid'] = 6;
                $fansDataAry['salt'] = 'aaaaaaaa';
                $fansDataAry['follow'] = !empty($allerganFansInfo)?($allerganFansInfo['subscribe']?$allerganFansInfo['subscribe']:1):1;
                $fansDataAry['followtime'] = !empty($allerganFansInfo)?strtotime($allerganFansInfo['insert_datetime']?$allerganFansInfo['insert_datetime']:0):0;
                $fansDataAry['unfollowtime'] = 0;
                $tag = '';
                if(!empty($fansDetailInfo)){
                  $tag = serialize($fansDetailInfo);
                  $tag = base64_encode($tag);
                }
                $fansDataAry['tag'] = $tag;
                $fansDataAry['updatetime'] = !empty($fansDetailInfo)?$fansDetailInfo['最后更新时间']:0;
                $fansDataAry['unionid'] = '';
                $abc=$weiqingModel->addInfo($fansDataAry);
                echo 'ImsMcMappingFans add re:'.$abc;
                // echo '||'.$weiqingModel->getLastSql().'||';
                // 更新艾尔建字段
                $this->allerganUpdate($value['member_id'],1);
                // echo 'ImsMcMappingFans表无openid:'.$value['openid'].','.'ImsMcMappingFans表新增成功';

                continue;
            }
            if($weiqingInfo['member_info']['uid'] > 0){
              echo 'o u:'.$weiqingInfo['member_info']['uid'];
              $weiqingAry = $this->updateDataAry($value,$weiqingInfo,'update');
              //在微擎会员表中找到相同用户，则直接把艾尔建会员信息更新到微擎会员
                $updateRes = $weiqingImsMemberModel->updateWhere($weiqingAry,$weiqingInfo['uid']);
                // if($updateRes){
                    $this->allerganUpdate($value['member_id'],1);
                // }
                // echo  '更新成功';
                continue;
            }
              // 新增到微擎会员表数据  
              $weiqingAry = $this->updateDataAry($value,$weiqingInfo,'add');
              $addRes = $weiqingImsMemberModel->addInfo($weiqingAry);
              echo 'ImsMember add reee:'.$addRes;
              // echo '||'.$weiqingImsMemberModel->getLastSql().'||';
              if($addRes){
                  # 会员新增成功，得到id更新fans表
                  $fansData = array();
                  $fansData['uid'] = $addRes;
                  $fansInfo = $weiqingModel->updateOpenidInfo($fansData,$value['openid']);
                  $this->allerganUpdate($value['member_id'],1);
                  // echo '新增成功';
              }
                
            }
            catch (\Exception $e)
            {
              // $code=$e->getCode();
              echo('err:'.$value['openid']);
              echo($e->getMessage());
            }
        }
    }
    // 组装待更新数据
    public function updateDataAry($value,$weiqingInfo,$type){
       // 用于储存微擎更新
      $weiqingAry = array();
      $weiqingAry['realname'] = $value['name']?$value['name']:'';
      if($value['sex'] == 'm'){
          $weiqingAry['gender'] = 1;
      }else if($value['sex'] == 'f'){
          $weiqingAry['gender'] = 2;
      }else{
          $weiqingAry['gender'] = 0;
      }
      $weiqingAry['mobile'] = $value['mobile']?$value['mobile']:'';
      $weiqingAry['uniacid'] = $this->allerganPublicId;
      #转化生日
      $weiqingAry['birthyear'] = '';
      $weiqingAry['birthmonth'] = '';
      $weiqingAry['birthday'] = '';
      if(!empty($value['birthday'])){
          $weiqingAry['birthyear'] = substr($value['birthday'],0,4);
          $weiqingAry['birthmonth'] = substr($value['birthday'],4,2);
          $weiqingAry['birthday'] = substr($value['birthday'],6,2);
      }
      if(!is_numeric($weiqingAry['birthyear'])){
        $weiqingAry['birthyear']=0;
      }
      if(!is_numeric($weiqingAry['birthmonth'])){
        $weiqingAry['birthmonth']=0;
      }
      if(!is_numeric($weiqingAry['birthday'])){
        $weiqingAry['birthday']=0;
      }
      $weiqingAry['resideprovince'] = $value['province_info']['name']?$value['province_info']['name']:'';
      $weiqingAry['residecity'] = $value['city_info']['name']?$value['city_info']['name']:'';
      $weiqingAry['occupation'] = $value['member_job']['job']?$value['member_job']['job']:'';
      $weiqingAry['createtime'] = $value['create_time']?$value['create_time']:0;
      $weiqingAry['nickname'] = !empty($weiqingInfo)?$weiqingInfo['nickname']:'';
      if($type == 'update'){
        return $weiqingAry;
      }
      //在微擎会员表中没找到相同用户，则直接新增艾尔建会员信息到微擎会员
        $weiqingAry['email'] = '';
        $weiqingAry['password'] = '';
        $weiqingAry['salt'] = '';
        $weiqingAry['groupid'] = 6;
        $weiqingAry['credit1'] = 0;
        $weiqingAry['credit2'] = 0;
        $weiqingAry['credit3'] = 0;     
        $weiqingAry['credit4'] = 0;
        $weiqingAry['credit5'] = 0;
        $weiqingAry['credit6'] = 0;
        // 对fans字段处理
        $headImgUrl = '';
        if(!empty($weiqingInfo)){
          $is_base64 = $this->is_base64($weiqingInfo['tag']);
          if($is_base64){
            $headImgUrl = base64_decode($weiqingInfo['tag']);
            $is_serialized = $this->is_serialized($headImgUrl);
            if ($is_serialized) {
              $headImgUrl = @$this->iunserializer($headImgUrl);
            }
          }
        }
        $weiqingAry['avatar'] = $headImgUrl?$headImgUrl["headimgurl"]:'';
        $weiqingAry['qq'] = '';
        $weiqingAry['vip'] = 0;
        $weiqingAry['constellation'] = 0;
        $weiqingAry['zodiac'] = '';
        $weiqingAry['telephone'] = 0;
        $weiqingAry['idcard'] = '';
        $weiqingAry['studentid'] = '';
        $weiqingAry['grade'] = '';
        $weiqingAry['address'] = '';
        $weiqingAry['zipcode'] = '';
        $weiqingAry['nationality'] = '';
        $weiqingAry['residedist'] = '';
        $weiqingAry['graduateschool'] = '';
        $weiqingAry['company'] = '';
        $weiqingAry['education'] = '';
        $weiqingAry['position'] = '';
        $weiqingAry['revenue'] = '';
        $weiqingAry['affectivestatus'] = '';
        $weiqingAry['lookingfor'] = '';
        $weiqingAry['bloodtype'] = '';
        $weiqingAry['height'] = '';
        $weiqingAry['weight'] = '';
        $weiqingAry['alipay'] = '';
        $weiqingAry['msn'] = '';
        $weiqingAry['taobao'] = '';
        $weiqingAry['site'] = '';
        $weiqingAry['bio'] = '';
        $weiqingAry['interest'] = '';
        $weiqingAry['pay_password'] = '';
        return $weiqingAry;
    }

    // 更新艾尔建会员表状态
    public function allerganUpdate($memberId,$isSyncWq){
        $allerganData = array();
        $allerganData['isSyncWq'] = $isSyncWq;
        $this->updateInfoWhere($allerganData,$memberId);
    }

    // 
    public function is_base64($str){
	  if(!is_string($str)){
	    return false;
	  }
	  return $str == base64_encode(base64_decode($str));
	}

	function is_serialized($data, $strict = true) {
	  if (!is_string($data)) {
	    return false;
	  }
	  $data = trim($data);
	  if ('N;' == $data) {
	    return true;
	  }
	  if (strlen($data) < 4) {
	    return false;
	  }
	  if (':' !== $data[1]) {
	    return false;
	  }
	  if ($strict) {
	    $lastc = substr($data, -1);
	    if (';' !== $lastc && '}' !== $lastc) {
	      return false;
	    }
	  } else {
	    $semicolon = strpos($data, ';');
	    $brace = strpos($data, '}');
	        if (false === $semicolon && false === $brace)
	      return false;
	        if (false !== $semicolon && $semicolon < 3)
	      return false;
	    if (false !== $brace && $brace < 4)
	      return false;
	  }
	  $token = $data[0];
	  switch ($token) {
	    case 's' :
	      if ($strict) {
	        if ('"' !== substr($data, -2, 1)) {
	          return false;
	        }
	      } elseif (false === strpos($data, '"')) {
	        return false;
	      }
	        case 'a' :
	      return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
	    case 'O' :
	      return false;
	    case 'b' :
	    case 'i' :
	    case 'd' :
	      $end = $strict ? '$' : '';
	      return (bool)preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
	  }
	  return false;
	}
	function iunserializer($value) {
	  if (empty($value)) {
	    return array();
	  }
	  if (!$this->is_serialized($value)) {
	    return $value;
	  }
	  if(version_compare(PHP_VERSION, '7.0.0', '>=')){
	    $result = unserialize($value, array('allowed_classes' => false));
	  }else{
	    if(preg_match('/[oc]:[^:]*\d+:/i', $seried)){
	      return array();
	    }
	    $result = unserialize($value);
	  }
	  if ($result === false) {
	    $temp = preg_replace_callback('!s:(\d+):"(.*?)";!s', function ($matchs){
	      return 's:'.strlen($matchs[2]).':"'.$matchs[2].'";';
	    }, $value);
	    return unserialize($temp);
	  } else {
	    return $result;
	  }
	}
}
