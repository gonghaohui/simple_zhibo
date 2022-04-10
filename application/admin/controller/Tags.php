<?php

namespace app\admin\controller;
use app\admin\model\ArticleTagsModel;
use think\Db;

class Tags extends Base
{
    public function data_list(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($tag_id) && $tag_id!=""){
                $map['tag_id'] = $tag_id;
            }
            if(isset($tag_name_jp)&&$tag_name_jp!=""){
                $map['tag_name_jp'] = ['like',"%" . $tag_name_jp . "%"];
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('article_tags')->where($map)->count();//计算总页面
            $articlesModel = new ArticleTagsModel();
            $od="tag_id desc";
            $lists = $articlesModel->getDatasByWhere($map, $Nowpage, $limits,$od);

            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }

        return $this->fetch('tags/data_list');
    }

    /**
     * editField 快捷编辑
     * @return \think\response\Json
     */
    public function editField(){
        extract(input());

        $res = Db::name($table)->where(['tag_id' => $id ])->setField($field , $value);
        if($res){
            writelog('更新字段成功',200);
            return json(['code' => 200,'data' => '', 'msg' => '更新字段成功']);
        }else{
            writelog('更新字段失败',100);
            return json(['code' => 100,'data' => '', 'msg' => '更新字段失败']);
        }
    }

    public function del_tag(){
        $tag_id = input('id');
        Db::startTrans();// 启动事务
        try{
            Db::name('article_tags')->delete($tag_id);
            Db::name('article_tags_relative')->where('tag_id',$tag_id)->delete();
            Db::commit();// 提交事务
            return json(['code' => 200, 'data' => '', 'msg' => '删除成功']);
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            return json(['code' => 100, 'data' => '', 'msg' => '删除失败']);
        }

    }



}