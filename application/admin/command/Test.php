<?php
namespace app\admin\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use Redis;
use think\Db;
class Test extends Command
{
    // 配置定时器的信息
    protected function configure()
    {
        $this->setName('test')
            ->setDescription('Command Test');
    }
    protected function execute(Input $input, Output $output)
    {
        //计算对应直播间room_{$lid}最近60秒有记录的用户,并把活动用户保存到对应的里room_{$lid}_user_online_num
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);

        $houses = Db::name('live_house')->order('lid','ASC')->select();
        $newTime = time();
        foreach ($houses as $house){
            $count = $redis->zCount('room_'.$house['lid'],$newTime-60,$newTime);
            $count = $count?$count:0;
            $redis->set('room_'.$house['lid'].'_user_online_num',$count);
        }


//        echo $count;
    }
}