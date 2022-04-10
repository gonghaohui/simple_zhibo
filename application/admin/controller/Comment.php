<?php

namespace app\admin\controller;

use app\admin\model\CommentModel;
use think\Db;

class Comment extends Base
{

    public function data_list(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($comment_id)&&$comment_id!=""){
                $map['comment_id'] = $comment_id;
            }else{
                $map['pid'] = 0;
            }
            if(isset($article_id)&&$article_id!=""){
                $map['article_id'] = $article_id;
            }
            if(isset($status)){
                if($status != -1){
                    $map['status'] = $status;
                }
            }else{
                $map['status'] = 2;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('comment')->where($map)->count();//计算总页面

            $commentModel = new CommentModel();
            $od="comment_id desc";
            $lists = $commentModel->getDatasByWhere($map, $Nowpage, $limits,$od);

            $lists = $commentModel->getCommentDownDatas($lists);
//            print_r($lists);
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }


        return $this->fetch('comment/data_list');

    }

    /**
     * [status_state 评论显示状态]
     * @return [type] [description]
     * @author
     */
    public function status_state()
    {
        extract(input());
        $commentModel = new CommentModel();
        $flag = $commentModel->statusState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 修改评论
     * @return mixed|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_comment(){
        if(request()->isPost()){
            $param = input();
            //不给修改内容
            unset($param['content']);

            $commentModel = new CommentModel();
            $res = $commentModel->update($param);
            if($res){
                return json(['code' => 200, 'data' => '', 'msg' => '修改成功']);
            }else{
                return json(['code' => 100, 'data' => '', 'msg' => '修改失败']);
            }
        }
        $comment_id = input('id');
        $data = Db::name('comment')->find($comment_id);
        $this->assign('data',$data);
        return $this->fetch('comment/edit_comment');
    }

    /**
     * 删除评论
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function del_comment(){
        $comment_id = input('id');
        $commentModel = new CommentModel();
        $flag = $commentModel->DelComment($comment_id);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);

    }

    public function verify_comment(){
        $comment_id = input('id');
        $pid = input('pid');
        if($pid == 0){
            //评论
            $res = Db::name('comment')->where('comment_id',$comment_id)->update(['status'=>1]);
        }else{
            //回复
            $res   = Db::name('comment')->where('comment_id',$comment_id)->update(['status'=>1]);
            $res_1 = Db::name('comment')->where('comment_id',$pid)->update(['down_comment'=>1,'status'=>1]);
        }
        if($res){
            return json(['code' => 200, 'data' => '', 'msg' => '审核通过']);
        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '审核失败，请检查']);
        }

    }

}