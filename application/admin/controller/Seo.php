<?php

namespace app\admin\controller;

use think\Db;

class Seo extends Base
{
    public function search_engine(){
        return $this->fetch('seo/search_engine');
    }

    public function get_today_update(){

        $today_start_time = strtotime( date('Y-m-d',time()).' 00:00:00' );
        $sendList = Db::name('articles')
            ->where('status',1)
            ->where('create_time','>',$today_start_time)
            ->field('article_id')
            ->select();
        foreach ($sendList as $list){
            echo 'https://www.scriptjp.com/article/'.$list['article_id'].'.html'.'<br>';
        }
    }

    /**
     * 推当天更新到bing
     */
    public function send_bing(){
        if(request()->isPost()){
            //当天开始时间
            $today_start_time = strtotime( date('Y-m-d',time()).' 00:00:00' );


            //先查看今天有没有更新过
            $sendList = Db::name('articles')
                ->where('status',1)
                ->where('create_time','>',$today_start_time)
                ->field('article_id')
//                ->limit(2)
                ->select();

            if(empty($sendList)){
                return ['code' => 100, 'data' => '', 'msg' => '没有检测到今天更新的数据'];
            }
            $urls = '';
//            $sendDatas = array();
//            $sendDatas['siteUrl'] = "https://www.scriptjp.com";
            foreach ($sendList as $list){
                $urls = $urls.'"https://www.scriptjp.com/article/'.$list['article_id'].'.html",';
            }
            $urls = rtrim($urls,',');
//            echo $urls;exit;


            $json = '{
            "siteUrl":"https://www.scriptjp.com",
            "urlList":['.$urls.']
            }';
//            exit;
//            $data_string =  json_encode($sendDatas);
            $bing_api_key = config('seo_api_key')['bing'];
            $ch = curl_init('https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey='.$bing_api_key);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            $res = curl_exec($ch);
            curl_close($ch);
            return ['code' => 200, 'data' => $res,'json' => $json, 'msg' => 'ok'];

        }else{
            return ['code' => 100, 'data' => '', 'msg' => 'only support post methods'];
        }


    }





}