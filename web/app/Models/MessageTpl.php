<?php
/**
  * 短信模板 Model类
  *
  * @author  HarveyCheng <harvey.cheng@eosi.com.cn>
  */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTpl extends Model
{	
	protected $table = "message_tpls";

    /**
     * Constructor
     *
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     *
     * 判断模板是否存在
     *
     * @return object || null
     */
	public function checkMessageTplExist($data)
	{
		// 赋值字段列表
        $this->getTableColumns(); 	
		foreach ($data as $key => $val) {
			if (!is_null($val) && in_array($key, $this->table_columns)) {
				$this->$key = $val;
			}
		}
		return $this->where("type", $this->type)
					->where("template_id", $this->template_id)
					->where("title", $this->title)
					->first();
	}

	/**
     *
     * 新增模板
     *
     * @return bool true || false
     */
	public function addMessageTpl($data)
	{
		// 赋值字段列表
        $this->getTableColumns(); 	
		foreach ($data as $key => $val) {
			if (!is_null($val) && in_array($key, $this->table_columns)) {
				$this->$key = $val;
			}
		}
		$this->code = intval($this->getMessageLastCode($this->type)) + 1;
		unset($this->id); // 释放有可能存在的ID
		return $this->save(); 
	}

	/**
     *
     * 获取最后一个模板的code
     *
     * @return code 
     */
	public function getMessageLastCode($type) 
	{
		return $this->where("type", $type)->orderBy("code", "desc")->value("code");
	}

	/**
     *
     * 更新模板
     *
     * @return bool false | true
     */
	public function saveMessageTpl($data) 
	{
		// 赋值字段列表
        $this->getTableColumns(); 	
		foreach ($data as $key => $val) {
			if (!is_null($val) && in_array($key, $this->table_columns)) {
				$this->$key = $val;
			}
		}
		if(!isset($this->id)) {
			return false;  // 无主键ID 不能更新
		}
		$this->updated_at = date("Y-m-d H:i:s");
		return $this->save();
	}

	/**
     *
     * 查找模板列表
     *
     * @return object list
     */
	public function getMessageTpls($condition=[]) 
	{
		return $this->where($condition)->orderBy("id", "desc")->get();
	}

	
	/**
     *
     * 根据ID 输出模板信息.
     *
     * @return object list
     */
	public function getMessageTplById($id) 
	{
		return $this->where("id", $id)->first();
	}

	/**
     *
     * 根据ID 输出模板信息.
     *
     * @return object list
     */
	public function getMessageTplByCode($code) 
	{
		return $this->where("code", $code)->first();
	}

	/**
     *
     * 根据ID 和 会员对象 输出标题.
     *
     * @return object list
     */
	public function getSmsTitle($message_tpl, $member) 
	{
		$title = $message_tpl->title;
		// TODO  
		return $title;
	}


	/**
     *
     * 根据ID 和 会员对象 输出内容.
     *
     * @return object list
     */
	public function getSmsContent($message_tpl, $member) 
	{
		$content = $message_tpl->content;
		// TODO  
		return $content;
	}

	/**
     *
     * 根据ID 和 会员对象 输出标题.
     *
     * @return object list
     */
	public function getWxTitle($message_tpl, $member) 
	{
		$title = $message_tpl->title;
		// TODO  
		return $title;
	}

	/**
     *
     * 根据ID 和 会员对象 输出内容.
     *
     * @return object list
     */
	public function getWxContent($message_tpl, $member) 
	{
		$content = $message_tpl->content;
		// TODO  
		return $content;
	}

}
