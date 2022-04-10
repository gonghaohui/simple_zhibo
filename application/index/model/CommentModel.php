<?php

namespace app\index\model;
use think\Model;
use think\Db;

class CommentModel extends Model
{
    protected $name = 'comment';

    public function getDownCommentAttr($value, $data){
        $result = array();
        if($data['down_comment'] == 1){
            $result = Db::name('comment')
                ->where('pid',$data['comment_id'])
                ->where('status',1)
                ->order('comment_id','asc')
                ->select();
            foreach ($result as $key => $comment){
                $result[$key]['between_time'] = $this->format_date(date('Y-m-d H:i:s',$comment['create_time']));
            }
        }
        return $result;
    }

    /**
     * 获取评论时间和当前时间之差
     * @param $dateStr
     * @return false|string
     */
    private function format_date($dateStr) {
        $limit = time() - strtotime($dateStr);
        $r = "";
        if($limit < 60) {
            $r = 'ちょうど今';//刚刚
        } elseif($limit >= 60 && $limit < 3600) {
            $r = floor($limit / 60) . '分前';
        } elseif($limit >= 3600 && $limit < 86400) {
            $r = floor($limit / 3600) . '時間前';
        } elseif($limit >= 86400 && $limit < 172800) {
            $r = '昨日';
        } elseif($limit >= 172800 && $limit < 2592000) {
            $r = floor($limit / 86400) . '日前';
        } elseif($limit >= 2592000 && $limit < 31104000) {
            //$r = floor($limit / 2592000) . '个月前';
            $r = date('Y-m-d',strtotime($dateStr));
        } else {
            $r = date('Y-m-d',strtotime($dateStr));
        }
        //return $r . "（" . $dateStr . "）";
        return $r;
    }

}