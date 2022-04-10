<?php

namespace app\admin\model;
use think\Model;


class LiveForbiddenWordsModel extends Model
{
    protected $name = 'forbidden_words';

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
     * [wordState 禁词状态]
     * @param $param
     * @return array
     */
    public function wordState($id,$num){
        if($num == 0){
            $msg = '禁用';
            $status = 0;
        }else{
            $msg = '启用';
            $status = 1;
        }

        $res = $this->where('fid',$id)->update(['status'=>$status]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => $msg.'成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }

    }

    /**
     * [insertWord 添加禁词信息]
     * @author
     */
    public function insertWord($param)
    {
        $res = $this->save($param);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '添加禁词成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '添加禁词失败'];
        }
    }

    /**
     * checkForbiddenWord 验证禁词的唯一性
     */
    public function checkForbiddenWord($word){
        $result = $this->where('word',$word)->find();
        if($result){
            return ['code' => 100, 'msg' => 'false'];
        }else{
            return ['code' => 200, 'msg' => 'true'];
        }
    }

    /**
     * batchDelWord 批量删除禁词
     * @param $param
     * @return array
     */
    public function batchDelWord($param){
        $res = LiveForbiddenWordsModel::destroy($param);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

    /**
     * usingWord 批量启用禁词
     * @param $param
     * @return array
     */
    public function usingWord($param){
        $res = $this->saveAll($param);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

    /**
     * banWord 批量禁用禁词
     * @param $param
     * @return array
     */
    public function banWord($param){
        $res = $this->saveAll($param);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

}