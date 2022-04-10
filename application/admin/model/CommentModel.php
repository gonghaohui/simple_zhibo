<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class CommentModel extends Model
{
    protected $name = 'comment';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * 根据搜索条件获取评论列表信息
     * @author
     */
    public function getDatasByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }

    /**
     * 重新构建层级分明的评论列表
     * @param $datas
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCommentDownDatas($datas){
        $lists = array();
        foreach ($datas as $key => $data){
            $datas[$key]['class'] = '│';
            $lists[] = $datas[$key];
//            if($data['down_comment']==1)
//            {
//                $this->getDownCommentByPid($datas[$key],$lists);
//            }
            $this->getDownCommentByPid($datas[$key],$lists);
//            $lists[] = $datas[$key];
        }
        return $lists;

    }

    /**
     * 递归获取评论回复（无限级）
     * @param $comment
     * @param $lists
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getDownCommentByPid($comment,&$lists){
        $datas = $this->where('article_id',$comment['article_id'])
            ->where('pid',$comment['comment_id'])
            ->order('comment_id','asc')
            ->select();
        foreach ($datas as $key => $data){
            $data['class'] = '└───>'.$comment['class'];
            $lists[] = $data;
            if($data['down_comment']==1){
                $this->getDownCommentByPid($data,$lists);
            }
        }

    }

    /**
     * [statusState 文章状态]
     * @param $param
     * @return array
     */
    public function statusState($id,$num){
        if($num == 1){
            $msg = '显示';
        }else{
            $msg = '不显示';
        }
        $res = $this->where('comment_id',$id)->update(['status'=>$num]);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => '成功'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => '失败'];
        }
    }

    /**
     * 删除评论
     * @param $comment_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function DelComment($comment_id){
        //获取这评论的数据
        $comment = $this->find($comment_id);
        //首先看这个评论有没有下级层级（可能无限级），把所有下级的ID都获取出来
        $data[] = $comment_id;
        $this->getDownCommentIdArray($comment_id,$data);

        Db::startTrans();// 启动事务
        try{
            Db::name('comment')->delete($data);
            //检查父ID是否需要修改down_comment的值
            if($comment['pid'] != 0){
                $count = Db::name('comment')->where('pid',$comment['pid'])->count();
                if($count == 0){
                    Db::name('comment')->where('comment_id',$comment['pid'])->update(['down_comment'=>0]);
                }
            }
            Db::commit();// 提交事务
            return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
        }

    }

    /**
     * 用递归方法获取该评论下的所有级别的评论ID
     * @param $comment_id
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getDownCommentIdArray($comment_id,&$data){

        $res = Db::name('comment')->where('pid',$comment_id)->select();
        if(!empty($res)){
            foreach ($res as $key => $re){
                $data[] = $re['comment_id'];
                $this->getDownCommentIdArray($re['comment_id'],$data);
            }
        }

    }

}