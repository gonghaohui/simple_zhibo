<?php

namespace app\admin\controller;

use app\admin\model\LinkModel;
use think\Db;


class Link extends Base
{

    /**
     * 友情链接列表
     * @return mixed|\think\response\Json
     */
    public function data_list(){
        if(request()->isAjax ()){
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('articles')->count();//计算总页面
            $linkModel = new LinkModel();
            $od="orderby desc";
            $lists = $linkModel->getDatasByWhere($Nowpage, $limits,$od);
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);

        }


        return $this->fetch('link/data_list');
    }


    /**
     * editField 快捷编辑
     * @return \think\response\Json
     */
    public function editField(){
        extract(input());
//        echo $field;
        $res = Db::name($table)->where(['link_id' => $id ])->setField($field , $value);
        if($res){
            writelog('更新字段成功',200);
            return json(['code' => 200,'data' => '', 'msg' => '更新字段成功']);
        }else{
            writelog('更新字段失败',100);
            return json(['code' => 100,'data' => '', 'msg' => '更新字段失败']);
        }
    }

    /**
     * [status_state 文章显示状态]
     * @return [type] [description]
     * @author
     */
    public function status_state()
    {
        extract(input());
        $articlesModel = new LinkModel();
        $flag = $articlesModel->statusState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 添加友情链接
     * @return array|mixed
     */
    public function add_link(){
        if(request()->isPost()){
//            extract(input());
            $param = input('post.');
            $res = Db::name('friend_link')->insert($param);
            if($res){
                return json(['code' => 200, 'data' => '', 'msg' => '添加成功']);
            }else{
                return json(['code' => 100, 'data' => '', 'msg' => '添加失败']);
            }
        }
        return $this->fetch('link/add_link');
    }

    /**
     * 删除友情链接
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del_link(){
        $id = input('param.id');

        $res = Db::name('friend_link')->delete($id);
        if($res){
            return json(['code' => 200, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '删除失败']);
        }
    }

}