<?php

namespace app\admin\model;
use think\Model;


class LiveHouseModel extends Model
{

    protected $name = 'live_house';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    /**
     * 根据搜索条件获取标签列表信息
     * @author
     */
    public function getDatasByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }

    /**
     * [insertLiveHouse 添加直播间]
     * @author
     */
    public function insertLiveHouse($param)
    {
        $res = $this->save($param);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '添加直播间成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '添加直播间失败'];
        }
    }

    /**
     * [houseState 直播间状态]
     * @param $param
     * @return array
     */
    public function houseState($id,$num){
        if($num == 0){
            $msg = '直播间禁用';
            $status = 0;
        }else{
            $msg = '直播间启用';
            $status = 1;
        }

        $res = $this->where('lid',$id)->update(['status'=>$status]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => $msg.'成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }

    }
}