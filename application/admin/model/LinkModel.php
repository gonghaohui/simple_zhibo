<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class LinkModel extends Model
{
    protected $name = 'friend_link';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    /**
     * 根据搜索条件获取列表信息
     * @author
     */
    public function getDatasByWhere($Nowpage, $limits,$od)
    {
        return $this->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }


    /**
     * [statusState 友情链接显示状态]
     * @param $param
     * @return array
     */
    public function statusState($id,$num){
        if($num == 1){
            $msg = '显示';
        }else{
            $msg = '不显示';
        }
        $res = $this->where('link_id',$id)->update(['is_show'=>$num]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '失败'];
        }
    }

}