<?php
namespace app\index\controller;

use think\Db;

class Common extends PcBase
{
    /**
     * md5加密
     */
    public function encrypt(){
        if($_POST){
            $type = input('type');
            $string = input('string');
            $text = '';
            if($type == 1){
                $text = strtoupper(md5($string));
            }elseif ($type == 2){
                $text = strtolower(md5($string));
            }elseif ($type == 3){
                $text = strtoupper(substr(md5($string),8,16));
            }else{
                $text = strtolower(substr(md5($string),8,16));
            }

            return json(['code'=>200,'data'=>$text]);
        }
    }



}