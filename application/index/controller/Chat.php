<?php
namespace app\index\controller;

use Redis;
use think\Db;
use think\Session;

class Chat extends PcBase
{

    public function one_chat(){

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        $redis->set('room_1_user_online_num','10');
//        $val =  $redis->get('name');
//        if(!$val){
//            echo "none";
//        }else{
//            echo $val;
//        }

//        session_start();
//        $redis->zAdd('room_1',time(),session_id());
//        echo session_id();

//        $a = $redis->zRevRange('room_1', 0, -1,true);
//        print_r($a);
        exit;
        return $this->fetch('chat/one_chat');

    }


    public function group_chat(){

        $room = input('room');
        if($room == ''){
            $room = 'room_1';
        }

        $this->assign('room',$room);
        return $this->fetch('chat/group_chat');

    }





}