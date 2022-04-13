<?php
namespace app\index\controller;

use Redis;
use think\Db;
use think\Cache;

class Live extends PcBase
{

    public function index(){

        $lid = input('lid');

        $res = Db::name('live_house')->find($lid);
        if(empty($res)){
            echo "没有该直播间";exit;
        }
//        if($res['status'] == 0){
//            echo "该直播间已关闭";exit;
//        }

        $this->assign('lid',$lid);
        $this->assign('data',$res);
        return $this->fetch('live/index');
    }

    /**
     * 输入密码进入房间
     * @return mixed
     */
    public function enter_house(){
        $lid = input('lid');
        if(!$lid){
            $houseData = Db::name('live_house')->where('status',1)->order('lid','asc')->find();
            if(!empty($houseData)){
                $lid = $houseData['lid'];
            }else{
                $lid = 0;
            }
        }

        $this->assign('lid',$lid);
        return $this->fetch('live/enter_house');
    }

    /**
     * 检验对应房间密码是否正确
     * @return \think\response\Json
     */
    public function check_house_pwd(){
        if(request()->isPost()){
            $lid = input('lid',0);
            $pwd = input('pwd');
            $res = Db::name('live_house')->where('lid',$lid)->find();
            if($lid == 0){
                return json(['code'=>100,'info'=>'error']);
            }
            if(empty($res)){
                return json(['code'=>100,'info'=>'没有该直播间']);
            }
//        if($res['status'] == 0){
//            return json(['code'=>100,'info'=>'该直播间已关闭']);
//        }
            if($pwd == $res['live_pwd']){
                return json(['code'=>200,'lid'=>$lid]);
            }else{
                return json(['code'=>100,'info'=>'密码错误']);
            }
        }

    }

    /**
     * 检测直播间状态
     * @return \think\response\Json
     */
    public function check_zhibo_status(){
        if(request()->isPost()){
            $lid = input('lid');
            if(!$lid){
                return json(['code'=>100,'info'=>'error']);
            }

            $res = Db::name('live_house')->where('lid',$lid)->find();
            if(empty($res)){
                return json(['code'=>100,'info'=>'none house']);
            }
            if($res['status'] == 0){
                //直播已关闭
                return json(['code'=>300,'info'=>'','url'=>'','online_user'=> 1]);

            }else{
                //正在直播当中
                $times = Cache::get('times');
                if(!$times){
                    $times = 1;
                }

                $online_num = $this->online_info_save_redis($lid)*$times;
                return json(['code'=>200,'info'=>'','url'=>$res['live_source'],'online_num'=> $online_num]);
            }
        }
    }

    /**
     * 记录用户信息存到redis
     */
    private function online_info_save_redis($lid){
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        session_start();
        $redis->zAdd('room_'.$lid,time(),session_id());
        $online_num = $redis->get('room_'.$lid.'_user_online_num');
        if(!$online_num){
            return 1;
        }else{
            return $online_num;
        }
    }

    public function auto_create_new_user(){
        $userSign = $this->create_rand_user_sign();
        //入库
        $inputData = array(
            'name' => $this->getChar(rand(2,5)),
            'explorer_key' => $userSign,
            'ip' => $this->get_real_ip(),
            'create_time' => time());
        $uid = Db::name('users')->insertGetId($inputData);
        $json = json_encode(
            array(
                'uid' => $uid,
                'name' => $inputData['name'],
                'sign' => $userSign
            )
        );
        return json(['code'=>200,'sign'=>$json]);

    }

    /**
     * 随机生成汉字
     * @return string
     */
    private function getChar($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }

    /**
     * 生成用户唯一标识
     * @return string
     */
    private function create_rand_user_sign(){

        $length = rand(5,9);
        $str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len=strlen($str)-1;
        $randstr='';
        for($i=0;$i<$length;$i++){
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return md5($randstr);
    }

    /**
     * 获取用户ip
     * @return string
     */
    private function get_real_ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $cip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"])){
            $cip = $_SERVER["REMOTE_ADDR"];
        }else{
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);
        return $cip;
    }


}