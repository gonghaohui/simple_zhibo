<?php
$host='127.0.0.1';
$user='root';
$password='';
$dbName='simple_zhibo';
$link=new mysqli($host,$user,$password,$dbName);

if ($link->connect_error){

    die("连接失败：".$link->connect_error);

}
$sql="select * from think_live_house";

$res=$link->query($sql);

$houses = $res->fetch_all();

$redis = new Redis();
$redis->connect('127.0.0.1',6379);
$newTime = time();

foreach ($houses as $house){
    $count = $redis->zCount('room_'.$house['lid'],$newTime-60,$newTime);
    $count = $count?$count:0;
    $redis->set('room_'.$house['lid'].'_user_online_num',$count);
}





?>