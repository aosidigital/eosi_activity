<?php  
header("Content-Type:text/html;charset=utf-8");

/*
|--------------------------------------------------------------------------
| 短信发送类
|--------------------------------------------------------------------------
|
| @author HarveyCheng <harvey.cheng@eosi.com.cn>
|
*/


class Sms
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apikey = "c58d08c55aeb9e1d42d937a42b150b47";
    }
    
    // 多平台短信发送
    public function sendTplSms($mobile, $template_id, $params=array(), $tpl_code) {
        $to = $mobile;
        $MessageTpl = App\Models\MessageTpl::where('template_id', $template_id)->first();

        $juhe_id = $MessageTpl->juhe_id;
        $content = $MessageTpl->content;
        // 短信内容
        if (isset($params['code'])) {
            $content = str_replace('#'.'code'.'#', $params['code'], $content);
        }
        if (isset($params['name'])) {
            $content = str_replace('#'.'name'.'#', $params['name'], $content);
        }
        if (isset($params['prize'])) {
            $content = str_replace('#'.'prize'.'#', $params['prize'], $content);
        }
        if (isset($params['time'])) {
            $content = str_replace('#'.'time'.'#', $params['time'], $content);
        }
        // 短信模版
        $templates = [
            'YunPian' => $template_id,
            'JuHe'    => $juhe_id
        ];
        // 模版数据
        $tempData = $params;
        $Sms_Log_info = PhpSms::make()->to($to)->template($templates)->data($tempData)->content($content)->agent('YunPian')->send(); 
        #\Log::info($Sms_Log_info);
        
        //此处获取信息到sms_log库
        $send_time = date('Y-m-d H:i:s');

        $sms_Model = new \App\Models\SmsLog;
        $smsInfo['code'] = $tpl_code;
        $smsInfo['phone_num'] = isset($to) ? $to : 0;
        $smsInfo['content'] = isset($content) ? $content : 0;
        $smsInfo['send_time'] = $send_time;
        $smsInfo['send_message'] = $Sms_Log_info['logs'][0]['result']['info'];
        $sms_Model->insertSmsLog($smsInfo);
        return $Sms_Log_info;
    }

    /**
     *
     * 自动发送 带模板的
     *
     *
     * @return Http response
     */
    public function sendTplSmsOld($mobile, $template_id, $params=array())
    {   
        
        $data=array(
            'tpl_id'=>$template_id,
            'tpl_value'=>urlencode(''),
            'apikey'=>$this->apikey,
            'mobile'=>$mobile
        );
        if (!empty($params)) {
            $msg = "";
            foreach ($params as $key => $val) {
                if (!empty($msg)) $msg .= "&";
                $msg .= ('#'.$key.'#').'='.urlencode($val);
            }
            $data['tpl_value'] = $msg;
        }

        // print_r ($data);

        return $this->doSendSms($data);
    }


    // 发送短信
    public function doSendSms ($data) {

        $ch = $this->init_ch();
        $res = $this->tpl_send($ch, $data);
        curl_close($ch);

        $result = $res['result'];
        $info = $res['info'];

        // print_r($result);
        // print_r($info);

        if ($result == false) {
            return ['code'=>-1, 'msg'=>'短信接口返回False'];
        }

        if ($info['http_code'] != 200) {
            return ['code'=>-2, 'msg'=>'短信接口访问异常'];
        }

        return json_decode($result, true);

    }

   
    public function init_ch()
    {
        $ch = curl_init();

        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $ch;
    }

    public function get_user($ch, $apikey)
    {
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/user/get.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $apikey)));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        return ["result"=>$result, "info"=>$info];
    }

    public function send($ch, $data){
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        return ["result"=>$result, "info"=>$info];
    }

    public function tpl_send($ch, $data){
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        return ["result"=>$result, "info"=>$info];
    }

    public function voice_send($ch, $data){
        curl_setopt ($ch, CURLOPT_URL, 'http://voice.yunpian.com/v2/voice/send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        return ["result"=>$result, "info"=>$info];
    }

}
