<?php

namespace app\admin\model;
use think\Model;


class UsersModel extends Model
{
    protected $name = 'users';

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
     * [userChatState 用户禁言状态]
     * @param $param
     * @return array
     */
    public function userChatState($id,$num){
        if($num == 0){
            $msg = '开启禁言';
            $chat_status = 0;
        }else{
            $msg = '开启聊天';
            $chat_status = 1;
        }

        $res = $this->where('uid',$id)->update(['chat_status'=>$chat_status]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => $msg.'成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }

    }

    /**
     * [userState 用户状态]
     * @param $param
     * @return array
     */
    public function userState($id,$num){
        if($num == 0){
            $msg = '禁用用户';
        }else{
            $msg = '开启用户';
        }

        $res = $this->where('uid',$id)->update(['status'=>$num]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => $msg.'成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }

    }

}