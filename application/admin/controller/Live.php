<?php

namespace app\admin\controller;

use app\admin\model\LiveForbiddenWordsModel;
use app\admin\model\LiveHouseModel;
use think\Db;

class Live extends Base
{

    /**
     * 直播房间列表
     */
    public function index(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($lid) && $lid!=""){
                $map['lid'] = $lid;
            }
            if(isset($live_name)&&$live_name!=""){
                $map['live_name'] = ['like',"%" . $live_name . "%"];
            }
            if(isset($status) && $status!=""){
                $map['status'] = $status;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('live_house')->where($map)->count();//计算总页面
            $liveHouseModel = new LiveHouseModel();
            $od="lid asc";
            $lists = $liveHouseModel->getDatasByWhere($map, $Nowpage, $limits,$od);
//            print_r($lists);

            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        return $this->fetch("live/index");


    }

    /**
     * 添加直播间
     */
    public function liveHouseAdd(){
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            $param['create_time'] = time();
            $liveHouseModel = new LiveHouseModel();
            $flag = $liveHouseModel->insertLiveHouse($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch('live/livehouseadd');
    }

    /**
     * [live_house_state 房间状态]
     */
    public function live_house_state(){
        extract(input());
        $liveHouseModel = new LiveHouseModel();
        $flag = $liveHouseModel->houseState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 删除房间
     */
    public function del_house(){
        $lid = input('id');

        $res = Db::name('live_house')->find($lid);
        if($res['status'] == 1){
            return json(['code' => 100, 'data' => '', 'msg' => '删除该房间前请先禁用该直播间']);
        }


        $result = Db::name('live_house')->delete($lid);
        if($result){
            return json(['code' => 200, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '删除失败']);
        }
    }

    /**
     * 修改房间信息
     */
    public function edit_house(){
        if(request()->isPost()){
            $param = input();
            $liveHouseModel = new LiveHouseModel();
            $res = $liveHouseModel->update($param);
            if($res){
                return ['code' => 200, 'data' => '', 'msg' => '修改成功'];
            }else{
                return ['code' => 100, 'data' => '', 'msg' => '修改失败'];
            }
        }
        $lid = input('id');
        $data = Db::name('live_house')->find($lid);
        $this->assign('data',$data);
        return $this->fetch('live/live_house_edit');
    }

    /**
     * 管理直播间
     */
    public function manage_house(){
        return $this->fetch('live/live_house_manage');
    }


    /**
     * 聊天禁词列表
     */
    public function forbidden(){

        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($fid) && $fid!=""){
                $map['fid'] = $fid;
            }
            if(isset($word)&&$word!=""){
                $map['word'] = ['like',"%" . $word . "%"];
            }
            if(isset($status) && $status!=""){
                $map['status'] = $status;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('forbidden_words')->where($map)->count();//计算总页面
            $forbiddenWordsModel = new LiveForbiddenWordsModel();
            $od="fid desc";
            $lists = $forbiddenWordsModel->getDatasByWhere($map, $Nowpage, $limits,$od);

            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }

        return $this->fetch("live/forbidden");

    }

    /**
     * editField 快捷编辑
     * @return \think\response\Json
     */
    public function editField(){
        extract(input());

        $res = Db::name($table)->where(['fid' => $id ])->setField($field , $value);
        if($res){
            writelog('更新字段成功',200);
            return json(['code' => 200,'data' => '', 'msg' => '更新字段成功']);
        }else{
            writelog('更新字段失败',100);
            return json(['code' => 100,'data' => '', 'msg' => '更新字段失败']);
        }
    }

    /**
     * 删除禁词
     */
    public function del_word(){
        $fid = input('id');

        $result = Db::name('forbidden_words')->delete($fid);
        if($result){
            return json(['code' => 200, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '删除失败']);
        }
    }

    /**
     * [forbidden_word_state 禁词状态]
     */
    public function forbidden_word_state()
    {
        extract(input());
        $forbiddenWordsModel = new LiveForbiddenWordsModel();
        $flag = $forbiddenWordsModel->wordState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [forbiddenWordAdd 添加禁词]
     */
    public function forbiddenWordAdd()
    {
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            $forbiddenWordsModel = new LiveForbiddenWordsModel();
            $flag = $forbiddenWordsModel->insertWord($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch('live/wordadd');
    }

    /**
     * checkForbiddenWord 验证禁词的唯一性
     */
    public function checkForbiddenWord(){
        extract(input());
        $forbiddenWordsModel = new LiveForbiddenWordsModel();
        $flag = $forbiddenWordsModel->checkForbiddenWord ($word);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * batchDelForbiddenWord 批量删除禁词
     * @return \think\response\Json
     */
    public function batchDelForbiddenWord(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的禁词！']);
        }
        $ids = explode(',',$ids);
        $ids = array_merge($ids);
        $forbiddenWordsModel = new LiveForbiddenWordsModel();
        $flag = $forbiddenWordsModel->batchDelWord($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * usingForbiddenWord 批量启用禁词
     * @return \think\response\Json
     */
    public function usingForbiddenWord(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的禁词！']);
        }
        $list = [];
        if($ids){
            $ids = explode(',',$ids);
            for($i=0;$i<count($ids);$i++){
                $param = [
                    'fid'=>$ids[$i],
                    'status'=>1
                ];
                $list[] = $param;
            }
        }
        $forbiddenWordsModel = new LiveForbiddenWordsModel();
        $flag = $forbiddenWordsModel->usingWord($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * banForbiddenWord 批量禁用禁词
     * @return \think\response\Json
     */
    public function banForbiddenWord(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的禁词！']);
        }
        $list = [];
        if($ids){
            $ids = explode(',',$ids);
            $ids = array_merge($ids);
            for($i=0;$i<count($ids);$i++){
                $param = [
                    'fid'=>$ids[$i],
                    'status'=>2
                ];
                $list[] = $param;
            }
        }
        $forbiddenWordsModel = new LiveForbiddenWordsModel();
        $flag = $forbiddenWordsModel->banWord($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

}