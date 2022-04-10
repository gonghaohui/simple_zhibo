<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 2018/8/2
 * Time: 22:28
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class PcBase extends Controller
{
    protected function _initialize()
    {
        //导航栏
        $navigation = Db::name('article_category')
//            ->cache('navigation',500)
            ->where('pid',0)
            ->where('show_navigation',1)
            ->where('status',1)
            ->order('sort','asc')
            ->select();
        $this->assign('navigation',$navigation);
    }
}