<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SmsLog extends Model
{
	protected $table = "sms_log";

	// 插入sms_log短信数据
	public function insertSmsLog($params = array())
	{
		return $this->insert($params);
	}
}
?>