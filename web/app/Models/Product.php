<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
	protected $table = "product";

	public function getDataByCondition($condition = [], $type = '', $extra = [])
    {
        $select_field = array_key_exists('select_field', $extra) ? $extra['select_field'] : '*';
        $group_field = array_key_exists('group_field', $extra) ? $extra['group_field'] : '';
        # 分页
        $first_row = array_key_exists('first_row', $extra) ? $extra['first_row'] : 0;
        $limit_row = array_key_exists('limit_row', $extra) ? $extra['limit_row'] : 0;
        # 排序
        $order_field = array_key_exists('order_field', $extra) ? $extra['order_field'] : 'created_at';
        $order_type = array_key_exists('order_type', $extra) ? $extra['order_type'] : 'DESC';
    	$self = $this->addSelect(\DB::raw($select_field))
    	    ->where(function ($query) use ($condition) {
                foreach ($condition as $key => $value) {
                    $query->whereRaw($key . $value);
                }
            })
            ->when($order_field, function ($query) use ($order_field, $order_type) {
                return $query->orderBy($order_field, $order_type);
            })
            ->when($limit_row, function ($query) use ($first_row, $limit_row) {
                return $query->skip($first_row)->take($limit_row);
            })
            ->when($group_field, function ($query) use ($group_field) {
                return $query->groupBy($group_field);
            });
        if ($type == 'all') return $self->get();
        if ($type == 'one') return $self->first();
        if ($type == 'cnt') return $self->count();
        return $self;
    }
}
?>