<?php

namespace App\Listeners;

use App\Events\WxMsgEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WxMsgListener
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
     * @param  WxMsgEvent  $event
     * @return void
     */
    public function handle(WxMsgEvent $event)
    {
        $input = $event->input;
        if (!isset($input['code']) || empty($input['code'])) {
            throw new \Exception("Code不能为空", 1104);
        }
        if (!isset($input['member_id']) || empty($input['member_id'])) {
            throw new \Exception("MemberId不能为空", 1104);
        }
        if (!isset($input['keynote']) || empty($input['keynote'])) {
            throw new \Exception("keynote不能为空", 1104);
        }
        
        $code = $input['code'];  // 识别威信类型
        $member_id = $input['member_id'];  // member object
        $keynote = $input['keynote'];
        $colornote = isset($input['colornote']) ? $input['colornote'] : [];
        $sendtype = isset($input['type']) ? $input['type'] : 0 ;//是否需要审核

        $messageWeixinModel = new \App\Models\MessageWeixin();
        $messageWeixinLogModel = new \App\Models\MessageWeixinLog();
        $memberModel = new \App\Models\Member();

        $member = $memberModel->where("openid",$member_id)->first();
        //会员openid是否存在
        if(!empty($member->openid)){
            if (is_null($member)) {
                throw new \Exception("会员不存在", 1104);
            }
            $message_weixin = $messageWeixinModel->getMessageWeixinByCode($code);
            if (is_null($message_weixin)) {
                throw new \Exception("微信模板不存在", 1104);
            }
            if (empty($member->openid)) {
                throw new \Exception("会员OpenId不存在", 1104);
            }
            if (empty($message_weixin->wx_template_id)) {
                throw new \Exception("微信消息模板ID为空", 1104);
            }
            $post_data = [];
            $post_data['message_weixin_id'] = $message_weixin->id;
            $post_data['member_id'] = $member_id;
            $post_data['name'] = $member->name;
            $post_data['wx_openid'] = $member->openid;
            $post_data['wx_template_id'] = $message_weixin->wx_template_id;

            $wx_url = $message_weixin->url;
            $input_url = isset($input['url']) ? $input['url'] : null;
            if (!is_null($input_url) && is_array($input_url)) {
                foreach ($input_url as $key => $val) {
                    $wx_url = str_replace("#".$key."#", $val, $wx_url);
                }
            }
            $post_data['url'] = $wx_url;
            $wx_head = $message_weixin->wx_head;
            $input_wx_head = isset($input['wx_head']) ? $input['wx_head'] : null;
            if (!is_null($input_wx_head) && is_array($input_wx_head)) {
                foreach ($input_wx_head as $key => $val) {
                    $wx_head = str_replace("#".$key."#", $val, $wx_head);
                }
            }
            $wx_foot = $message_weixin->wx_foot;
            $input_wx_foot = isset($input['wx_foot']) ? $input['wx_foot'] : null;
            if (!is_null($input_wx_foot) && is_array($input_wx_foot)) {
                foreach ($input_wx_foot as $key => $val) {
                    $wx_foot = str_replace("#".$key."#", $val, $wx_foot);
                }
            }
            $m = 1;
            $post_data['colornote'] = "";
            foreach ($keynote as $key => $val) {
                $post_data['keynote'.$m] = $val;
                $post_data['colornote'.$m] = '#173177';
                if (in_array($key, array_keys($colornote)) && strlen($colornote[$key]) > 0) {
                    $post_data['colornote'.$m] = $colornote[$key];
                }
                $post_data['colornote'] .= $post_data['colornote'.$m].",";
                $m++;
            }
            $post_data['colornote'] = substr($post_data['colornote'], 0, -1);
            $post_data['first'] = $wx_head;
            $post_data['remark'] = $wx_foot;
            $post_data['created_at'] = date("Y-m-d H:i:s");
            $post_data['updated_at'] = date("Y-m-d H:i:s");
            $post_data['sendtype']=$sendtype;
            $weixin = new \Weixin();
            $send_data = [];
            $send_data['first'] = ['value'=> $wx_head, 'color'=>'#213375'];
            for ($i=1; $i <= 10; $i++) {
                if (isset($post_data['keynote'.$i]) && $post_data['keynote'.$i] !== null) {
                    $send_data['keyword'.$i] = ['value'=> $post_data['keynote'.$i], 'color'=>$post_data['colornote'.$i]];
                }
            }
            $send_data['remark'] = ['value'=> $wx_foot, 'color'=>'#fd7a07'];
            $post_data['member_id']=$member->member_id;
            $res = $messageWeixinLogModel->addMessageWeixinLog($post_data);
            $send_result = $weixin->sendTplWxMsg($member_id, $message_weixin->wx_template_id, $wx_url, $send_data);
            if (!$res) {
                throw new \Exception("微信消息保存失败", 1104);
            }
        }
        return true;
    }

}
