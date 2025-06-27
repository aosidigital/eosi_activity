<?php 
namespace App\Http\Controllers;
use DB;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\LdActivity;
use App\Models\LdActivityMember;
use App\Models\LdSaveCode;
use App\Models\LdPrize;
use App\Models\LdBlackList;
use Illuminate\Support\Facades\Session;

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$allow_origin = [
    'http://10.1.1.245',
    'https://echo.eoiyun.com'
];
if(in_array($origin, $allow_origin)){
    header('Access-Control-Allow-Origin:'.$origin);
}
header('Access-Control-Allow-Credentials:true');


class LuckdrawController extends Controller {

	/**
     * 初始化
     * 
     * @param    {string}
     */
    public function __construct()
    {
        $this->codetime = 1800;// 验证码过期时间
        $this->Member = new Member();
        $this->LdActivity = new LdActivity();
        $this->LdActivityMember = new LdActivityMember();
        $this->LdSaveCode = new LdSaveCode();
        $this->LdPrize = new LdPrize();
        $this->LdBlackList = new LdBlackList();
    }


    /**
     * 获取用户信息
     */
    public function getuserinfo(Request $request){
        $input = $request->all();
        $openid = isset($input['openid'])?$input['openid']:'';
        $head = isset($input['headimgurl'])?$input['headimgurl']:'';
        $name = isset($input['nickname'])?$input['nickname']:'';
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $istrue = $this->LdActivity->where("id",$activity)->first();
        // 活动信息
        if($istrue){
            if($istrue['is_stop'] == 1 && $istrue['end_time'] && time() >= strtotime(date("Y-m-d", strtotime($istrue['end_time']))) + 86400){
                return response()->json(['code' => 300, 'msg' => '抱歉，该场活动已结束', "data" => '', 'use_time' => 2]);
            }
        }else{
            return response()->json(['code' => 300, 'msg' => '未找到活动信息', "data" => '', 'use_time' => 2]);
        }
        // 用户信息
        if($openid){
            $res = $this->Member->where(array("openid" => $openid))->first();
            if($res){
                return response()->json(['code' => 200, 'msg' => '成功', "data" => $res['member_id'], 'use_time' => 2]); 
            }else{
                $data = array();
                $data['openid'] = $openid;
                $data['headimgurl'] = $head;
                $data['nickname'] = $name;
                $data['create_time'] = time();
                $membera = $this->Member->insertGetId($data);
                return response()->json(['code' => 200, 'msg' => '成功', "data" => $membera, 'use_time' => 2]); 
            }
        }
        return response()->json(['code' => 200, 'msg' => '成功', "data" => '', 'use_time' => 2]); 
    }


    /**
     * pc抽奖接口
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function operation(Request $request){
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        if($activity){
            $res = $this->LdActivityMember->addSelect(\DB::raw("ld_activity_member.*,member.nickname,member.headimgurl"))
                ->leftJoin('member', 'ld_activity_member.member_id', '=', 'member.member_id')
                ->where("activity_id",$activity)->orderBy("ld_activity_member.id",'DESC')->get();
            foreach ($res as $key => $value) {
                $value['headimgurl'] = empty($value['headimgurl']) ? 'https://echocdn.eoiyun.com/upload_static/5e75625adae19c71bbce2800/64eff9a310f5cd1553e422a0/1693557529944.png' : $value['headimgurl'];
            }
            return response()->json(['code' => 200, 'msg' => '成功', "data" => $res, 'use_time' => 2]); 
        }
        return response()->json(['code' => 300, 'msg' => '信息缺失', "data" => '', 'use_time' => 2]);
    }

    /**
     * 验证活动有效性
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function very_activity(Request $request){
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $res = $this->LdActivity->where("id",$activity)->first();
        $parze = $this->LdPrize->where("type", 1)->get();
        if($res){
            return response()->json(['code' => 200, 'msg' => '成功', "data" => $res, 'parze' => $parze]);
        }
        return response()->json(['code' => 200, 'msg' => '活动链接错误', "data" => false]);
    }

    /**
     * pc开始抽奖接口（活动1）
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function startpc_dc(Request $request){
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        if($activity){
            $prizes = $this->LdActivity->where("id",$activity)->first();
            if($prizes && $prizes['is_stop'] != 1){
                $info = $this->LdActivityMember->addSelect(\DB::raw("ld_activity_member.*,ld_activity.name as aname"))
                        ->leftJoin('ld_activity', 'ld_activity_member.activity_id', '=', 'ld_activity.id')
                        ->where("activity_id",$activity)
                        // ->groupBy("member_id")
                        ->get()->toArray();
                shuffle($info);

                // 删除黑名单用户
                $black_list = $this->LdBlackList->get()->toArray();
                foreach ($black_list as $key => $value) {
                    foreach ($info as $k => $v) {
                        if($value['mobile'] == $v['member_id']){
                            unset($info[$k]);
                        }
                    }
                }
                
                // 设置中奖奖品
                $num = array();
                // 获取奖品动态数据
                $count = 0;
                $jp_cont = $this->LdPrize->where("id", $prizes['ac_type'])->first();
                if($jp_cont){
                    $count = $jp_cont['grade'];
                }
                // 生成中奖数组
                for ($i=0; $i < $count; $i++) { 
                    $num[count($num)] = $prizes['ac_type'];
                }
                $s = 0;
                foreach ($info as $keya => $valuea) {
                    if(isset($num[$s])){
                        $data_n = array();
                        $data_n['is_win'] = 1;
                        $data_n['prize_id'] = $num[$s];
                        $oks = $this->LdActivityMember->where("id",$valuea['id'])->update($data_n);
                        # 记录开奖详情修改信息
                        \Log::info("记录开奖详情修改信息");
                        \Log::info($valuea);
                        \Log::info("记录开奖结果");
                        \Log::info($oks);
                    }else{
                        break;
                    }
                    $s++;
                }
                // 修改活动状态
                $this->LdActivity->where("id",$activity)->update(array("is_stop" => 1, "end_time" => date("Y-m-d H:i:s")));
                # 发送钉钉消息提醒
                $webhook = env("URL_ONE");
                $data = array ('msgtype' => 'text',
                    'text' => array (
                        'content' =>$prizes['name']."，开起了抽奖。",
                    ),
                    'at' => array(
                        'atMobiles' => array(env("MOBILE_ONE"))
                    )
                );
                $data_string = json_encode($data);
                $this->request_by_curl($webhook,$data_string);
                return response()->json(['code' => 200, 'msg' => '成功', "data" => '', 'use_time' => 2]);
            }
        }
        return response()->json(['code' => 300, 'msg' => '抽奖失败', "data" => '', 'use_time' => 2]);
    }


    /**
     * 获取中间人员
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function selectWinnings(Request $request){
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        if($activity){
            $res = $this->LdActivityMember->addSelect(\DB::raw("ld_activity_member.*,member.nickname,member.headimgurl"))
                ->leftJoin('member', 'ld_activity_member.member_id', '=', 'member.member_id')
                ->where("activity_id",$activity)->where("is_win", 1)->orderBy("ld_activity_member.id",'DESC')->get();
            foreach ($res as $key => $value) {
                $value['headimgurl'] = empty($value['headimgurl']) ? 'https://echocdn.eoiyun.com/upload_static/5e75625adae19c71bbce2800/64eff9a310f5cd1553e422a0/1693557529944.png' : $value['headimgurl'];
            }
            return response()->json(['code' => 200, 'msg' => '成功', "data" => $res, 'use_time' => 2]); 
        }
        return response()->json(['code' => 300, 'msg' => '信息缺失', "data" => '', 'use_time' => 2]);
    }

    /**
     * 添加会员活动记录
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function activitylog(Request $request){
        $input = $request->all();
        \Log::info("调用添加活动数据接口");
        \Log::info($input);
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $member_id = isset($input['member_id'])?$input['member_id']:'';
        if($member_id && $activity){
            # 是否存在数据
            $is_am = $this->LdActivityMember->where("activity_id",$activity)->where("member_id",$member_id)->first();
            if(!$is_am){
                $data = array();
                $data['activity_id'] = $activity;
                $data['member_id'] = $member_id;
                $data['created_at'] = date("Y-m-d H:i:s");
                $res = $this->LdActivityMember->insertGetId($data);
                \Log::info("活动数据添加成功");
                \Log::info($data);
                if($res){
                    return response()->json(['code' => 200, 'msg' => '成功', "data" => '', 'use_time' => 2]);
                }else{
                    return response()->json(['code' => 300, 'msg' => '加入失败', "data" => '', 'use_time' => 2]);
                }
            }else{
                return response()->json(['code' => 200, 'msg' => '成功', "data" => '', 'use_time' => 2]);
            }
        }
        return response()->json(['code' => 300, 'msg' => '信息缺失', "data" => '', 'use_time' => 2]);
    }
    
    /**
     * 获取活动信息
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function getinfo(Request $request){
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $istrue = $this->LdActivity->where("id",$activity)->first();
        if($istrue){
            if($istrue['start_time'] > date("Y-m-d H:i:s", time() + 3600)){
                return response()->json(['code' => 300, 'msg' => '活动未开始，请活动前1小时内来签到', "data" => '', 'use_time' => 2]);
            }else if($istrue['final_time'] < date("Y-m-d H:i:s", time())){
                return response()->json(['code' => 300, 'msg' => '抱歉，该场活动已结束', "data" => '', 'use_time' => 2]);
            }else{
                return response()->json(['code' => 200, 'msg' => '', "data" => '', 'use_time' => 2]);
            }
        }else{
            return response()->json(['code' => 300, 'msg' => '未找到活动信息', "data" => '', 'use_time' => 2]);
        }
    }

    /**
     * 是否已加入中奖
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function isjoin(Request $request){
        $input = $request->all();
        $member_id = $input['member_id'];
        $activity = base64_decode($input['activity']);
        $istrue = $this->LdActivity->where("id",$activity)->first();
        if($istrue){
            if($istrue['start_time'] <= date("Y-m-d H:i:s", time() + 3600)){
                $array = array();
                # 是否报名
                $members = $this->LdActivityMember->addSelect(\DB::raw("ld_activity_member.*,ld_prize.name,member.nickname,member.headimgurl"))
                    ->leftJoin('ld_prize', 'ld_prize.id', '=', 'ld_activity_member.prize_id')
                    ->leftJoin('member', 'member.member_id', '=', 'ld_activity_member.member_id')
                    ->where("member.member_id",$member_id)->where("activity_id",$activity)->first();
                $array['member'] = $members;
                $array['activity'] = $istrue;
                return response()->json(['code' => 200, 'msg' => '活动信息', "data" => $array, 'use_time' => 2]);
            }else{
                return response()->json(['code' => 200, 'msg' => '活动未开始', "data" => '', 'use_time' => 2]);
            }
        }else{
            return response()->json(['code' => 300, 'msg' => '未找到活动信息', "data" => '', 'use_time' => 2]);
        }
    }


    /**
     * 中奖历史
     *
     * @param    {string}
     * @return   [type]     [description]
     */
    public function list(Request $request){
        $input = $request->all();
        $res = array();
        $member_id = isset($input['member_id'])?$input['member_id']:'';
        $type = isset($input['type'])?$input['type']:0;
        if($member_id){
            $res = $this->LdActivityMember->addSelect(\DB::raw("ld_activity_member.*,member.nickname,member.mobile,ld_prize.name as pname,ld_activity.name as aname,ld_activity.start_time,ld_activity.end_time"))
            ->leftJoin('member', 'ld_activity_member.member_id', '=', 'member.member_id')
            ->leftJoin('ld_prize', 'ld_activity_member.prize_id', '=', 'ld_prize.id')
            ->leftJoin('ld_activity', 'ld_activity_member.activity_id', '=', 'ld_activity.id')
            ->where("ld_activity_member.member_id",$member_id)->where("is_win",1)->where("ac_type",$type)->get();
        }
        return response()->json(['code' => 200, 'msg' => '成功', "data" => $res, 'use_time' => 2]); 
    }
    

    /**
     * 核销
     * @return [type] [description]
     */
    public function exchanged(Request $request){
        $input = $request->all(); 
        $id = isset($input['id'])?$input['id']:'';
        $member_id = isset($input['member_id'])?$input['member_id']:'';
        if($id && $member_id){
            $res = $this->LdActivityMember->where("id",$id)->where("member_id",$member_id)->update(array("exchange"=>1));
            if($res){
                return response()->json(['code' => 200, 'msg' => '兑换成功', "data" => '', 'use_time' => 2]); 
            }
        }
        return response()->json(['code' => 300, 'msg' => '兑换失败', "data" => '', 'use_time' => 2]);
    }

    /**
    * 发送钉钉信息
    **/
    public function request_by_curl($remote_server, $post_string) {  
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);                
        return $data;  
    }
}