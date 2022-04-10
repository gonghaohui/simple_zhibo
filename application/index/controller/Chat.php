<?php
namespace app\index\controller;

use think\Db;


class Chat extends PcBase
{

    public function one_chat(){



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