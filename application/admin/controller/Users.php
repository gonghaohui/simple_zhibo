<?php

namespace app\admin\controller;

use app\admin\model\UsersModel;
use think\Db;

class Users extends Base
{
    /**
     * 会员列表
     */
    public function index(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($uid) && $uid!=""){
                $map['uid'] = $uid;
            }
            if(isset($name)&&$name!=""){
                $map['name'] = ['like',"%" . $name . "%"];
            }
            if(isset($explorer_key)&&$explorer_key!=""){
                $map['explorer_key'] = ['like',"%" . $explorer_key . "%"];
            }
            if(isset($online_status) && $online_status!=""){
                $map['online_status'] = $online_status;
            }
            if(isset($chat_status) && $chat_status!=""){
                $map['chat_status'] = $chat_status;
            }
            if(isset($status) && $status!=""){
                $map['status'] = $status;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('users')->where($map)->count();//计算总页面
            $usersModel = new UsersModel();
            $od="uid desc";
            $lists = $usersModel->getDatasByWhere($map, $Nowpage, $limits,$od);

            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        return $this->fetch("users/index");
    }

    /**
     * [users_chat_state 会员禁言状态]
     */
    public function users_chat_state()
    {
        extract(input());
        $usersModel = new UsersModel();
        $flag = $usersModel->userChatState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [users_state 会员状态]
     */
    public function users_state()
    {
        extract(input());
        $usersModel = new UsersModel();
        $flag = $usersModel->userState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

}