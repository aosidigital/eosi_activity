<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 统一跳转方法 错误的返回
     *
     * $error_no   错误代码 默认 0 表示正确
     * $error_msg  错误原因描述
     * 
     * @return Http response
     */
    public function jsonReturnFalse($error_no=1, $error_msg='') {

    	// TODO 添加日志记录

        // 屏蔽ERP接口的错误信息
        $url = \Request::url();
        $pos = stripos($url, '/erp/');
        // 不屏蔽的错误类型
        $no_list = [204, 205];

        if ($pos && !in_array($error_no, $no_list)) {
            //如果出错则给管理员发站内消息，不提示收银端
            $title = "ERP接口异常";
            systemRemind($error_msg, $title);
            // $this->dingdingRemind($title.' '.$error_msg);
            return response()->json(['error_no' => 0, 'error_msg' => '', "results" => []]); // 万序修改后 去掉
        }

        //否则返回错误信息
        return response()->json(['error_no' => $error_no, 'error_msg' => $error_msg, "results" => []]); 
    }

    /**
     * 统一跳转方法 正确的返回
     *
     * $results    结果集 []类型
     * $error_msg   正确返回的描述
     * 
     * @return Http response
     */
    public function jsonReturnTrue($results=[], $error_msg="") {

    	// TODO 添加日志记录

        // header('Access-Control-Allow-Origin: *');
    	return response()->json(['error_no' => 0, 'error_msg' => $error_msg, "results" => $results]);
    }

    function dingdingRemind($msg) {
        if (env('APP_ENV') != 'product') return;

        $url = 'http://message.eosi.com.cn:10000/mysql/new_msg.php?user_name=forrest,kevin,wenliang&from=crm&msg='.urlencode($msg);
        $fp = fopen($url, 'r');
        fclose($fp);
    }

    /**
     * 处理接口参数
     */
    public function Handlerequest($request){
        if(empty($request)){
            $body = file_get_contents('php://input');
            $bodyStr = htmlspecialchars_decode($body);
            return json_decode($bodyStr,true);
        }else{
            return $request;
        }
    }
}
