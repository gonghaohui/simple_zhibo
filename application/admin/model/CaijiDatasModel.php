<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class CaijiDatasModel extends Model
{
    protected $name = 'caiji_datas';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * 根据搜索条件获取采集内容列表信息
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
     * [delBeforeCheck 删除该分类前先检查该分类下还有没有分类，有的话不给删除，没有的话就可以]
     * @param $caiji_id
     */
    public function delBeforeCheck($caiji_id){
        $res = $this->where('caiji_id', $caiji_id)->update(['publish'=>2]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
        }

    }

    public function restoreBeforeCheck($caiji_id){
        $res = $this->where('caiji_id', $caiji_id)->update(['publish'=>0]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '恢复成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '恢复失败'];
        }
    }


    /**
     * 批量删除采集内容，其实就是把publish这个字段改为2
     * @param $param
     */
    public function batchDelCaijiDatas($param){
        $res = $this->where('caiji_id','in',$param)->update(['publish' => 2]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }


}