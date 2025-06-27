<?php

namespace App\Listeners;

use App\Events\SendSmsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSmsEvent  $event
     * @return void
     */
    public function handle(SendSmsEvent $event)
    {
        $input = $event->input;
        if (!isset($input['type']) || empty($input['type'])) {
            throw new \Exception("丢失参数type", 604);
        }

        if (!isset($input['phone']) || empty($input['phone'])) {
            throw new \Exception("丢失参数phone", 604);
        }

        if (!isset($input['tpl_code']) || empty($input['tpl_code'])) {
            throw new \Exception("丢失参数tpl_code", 604);
        }

        $type = $input['type'];
        $phone = $input['phone'];
        $tpl_code = $input['tpl_code'];

        if (!isset($input['params']) || empty($input['params'])) {
            throw new \Exception("丢失参数params", 604);
        }

        $params = $input['params'];
        $paramsArr = json_decode($params, true); # json 转换
        $messageTplModel = new \App\Models\MessageTpl();
        $message_tpl = $messageTplModel->getMessageTplByCode($tpl_code); //获取注册短信模板ID
        # 验证短信模板是否存在
        if(intval($message_tpl->template_id) < 1 ){
            throw new \Exception("未配置短信模板,请确认是否填写。", 605);
        }
        $sms = new \Sms();
        $send_result = $sms->sendTplSms($phone, $message_tpl->template_id, $paramsArr , $input['tpl_code']);
        if ($send_result['success']) {
            // 发送成功
            \Log::info('发送成功');
        }else {
            // 发送失败
            \Log::info('发送失败');
            \Log::info($send_result);
        }
    }
}
