<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LdActivityMember extends Model
{
	protected $table = "ld_activity_member";

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
            ->leftJoin('ld_prize', 'ld_activity_member.prize_id', '=', 'ld_prize.id')
            ->leftJoin('member', 'ld_activity_member.member_id', '=', 'member.member_id')
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

    public function getDataByCondition_new($condition = [], $type = '', $extra = [])
    {
        $select_field = array_key_exists('select_field', $extra) ? $extra['select_field'] : '*';
        $group_field = array_key_exists('group_field', $extra) ? $extra['group_field'] : '';
        # 分页
        $first_row = array_key_exists('first_row', $extra) ? $extra['first_row'] : 0;
        $limit_row = array_key_exists('limit_row', $extra) ? $extra['limit_row'] : 0;
        # 排序
        $order_field = array_key_exists('order_field', $extra) ? $extra['order_field'] : 'created_at';
        $order_type = array_key_exists('order_type', $extra) ? $extra['order_type'] : 'DESC';

        # 排序2
        $order_field2 = array_key_exists('order_field2', $extra) ? $extra['order_field2'] : 'created_at';
        $order_type2 = array_key_exists('order_type2', $extra) ? $extra['order_type2'] : 'DESC';
        $self = $this->addSelect(\DB::raw($select_field))
            ->leftJoin('ld_prize', 'ld_activity_member.prize_id', '=', 'ld_prize.id')
            ->leftJoin('member', 'ld_activity_member.member_id', '=', 'member.member_id')
            ->leftJoin('ld_activity', 'ld_activity.id', '=', 'ld_activity_member.activity_id')
    	    ->where(function ($query) use ($condition) {
                foreach ($condition as $key => $value) {
                    $query->whereRaw($key . $value);
                }
            })
            ->when($order_field, function ($query) use ($order_field, $order_type) {
                return $query->orderBy($order_field, $order_type);
            })
            ->when($order_field2, function ($query) use ($order_field2, $order_type2) {
                return $query->orderBy($order_field2, $order_type2);
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