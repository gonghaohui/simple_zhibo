<?php
namespace QL\Ext;

/**
 * @Author: Jaeger <hj.q@qq.com>
 * @Date:   2015-07-15 23:27:52
 * @Last Modified by:   Jaeger
 * @Last Modified time: 2016-07-09 00:45:08
 * @version         1.1.1
 * 图片下载扩展
 */

use phpQuery;
use think\Config;
class DImage extends AQuery
{
    private $attr;

    //直接下载图片的
//    public function run(array $args)
//    {
//        $args = array_merge(array(
//            'image_path' => '/images',
//            'base_url' => '',
//            'attr' => array('src'),
//            'callback' => null
//            ),$args);
//        $doc = phpQuery::newDocumentHTML($args['content']);
//        $http = $this->getInstance('QL\Ext\Lib\Http');
//        $imgs = pq($doc)->find('img');
//        foreach ($imgs as $img) {
//            $src = $this->getSrc($img,$args);
//            $array = explode('?',$src);
//            $src = $array[0];
//            $localSrc = rtrim($args['image_path'],'/').'/'.$this->makeFileName($src);
//            $savePath = rtrim($args['www_root'],'/').'/'.ltrim($localSrc,'/');
//            $this->mkdirs(dirname($savePath));
////                $stream = $http->get($src);
//            $stream = @file_get_contents($src).' ';
////                print_r($http_response_header);exit;
//            if($http_response_header[0] == 'HTTP/1.1 200 OK'){
//                @file_put_contents($savePath,$stream.' ');
//            }else{
//                return json(['code' => 100, 'data' => '', 'msg' => '下载图片失败']);
//            }
//            pq($img)->attr($this->attr,$localSrc);
//            $args['callback'] && $args['callback'](pq($img));
//        }
//        return $doc->htmlOuter();
//    }

    //通过中转服务器下载图片的
    public function run(array $args)
    {
        $args = array_merge(array(
            'image_path' => '/images',
            'base_url' => '',
            'attr' => array('src'),
            'callback' => null
        ),$args);
        $doc = phpQuery::newDocumentHTML($args['content']);
        $http = $this->getInstance('QL\Ext\Lib\Http');
        $imgs = pq($doc)->find('img');
        foreach ($imgs as $img) {
            $src = $this->getSrc($img,$args);
            $array = explode('?',$src);
            $src = $array[0];

            //发送请求到中转服务器下载图片
            $post_data = array(
                'action' => 'down_load_img',
                'img' => $src

            );
            $config = Config::load('config.php');
            $res = $this->send_post($config['download_img_server']['url'], $post_data);
            $resData = json_decode($res,true);
            if($resData['code'] == 100){
                return json(['code' => 100, 'data' => '', 'msg' => '下载图片失败']);
            }
            $download_link = $config['download_img_server']['domain'].$resData['download_link'];


            $localSrc = rtrim($args['image_path'],'/').'/'.$this->makeFileName($src);
            $savePath = rtrim($args['www_root'],'/').'/'.ltrim($localSrc,'/');
            $this->mkdirs(dirname($savePath));
            $stream = @file_get_contents($download_link).' ';
            if($http_response_header[0] == 'HTTP/1.1 200 OK'){
                @file_put_contents($savePath,$stream.' ');
            }else{
                return json(['code' => 100, 'data' => '', 'msg' => '下载图片失败1']);
            }
//            return;
            pq($img)->attr($this->attr,$localSrc);
            $args['callback'] && $args['callback'](pq($img));
        }
        return $doc->htmlOuter();
    }

    private function send_post($url, $post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if($http_response_header[0] == 'HTTP/1.1 200 OK')
        {
            return $result;
        }else{
            return json_encode(['code' => 100, 'download_link' => '', 'msg' => '中转服务器链接出错']);
        }
    }


    public function mkdirs($dir)
    {
        if(!is_dir($dir))
        {
            if(!$this->mkdirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }

    public function makeFileName($src)
    {
        return md5($src).'.'.pathinfo($src, PATHINFO_EXTENSION);
    }

    public function getSrc($img,$args)
    {
        $src = $args['base_url'];
        is_string($args['attr']) && $args['attr'] = array($args['attr']);
        foreach ($args['attr'] as $attr) {
            $val = pq($img)->attr($attr);
            if(!empty($val)){
                $this->attr = $attr;
                $src .= $val;
                break;
            }
        }
        return $src;
    }
}