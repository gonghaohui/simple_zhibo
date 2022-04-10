<?php

namespace app\admin\controller;

use app\admin\model\ArticleCategoryModel;
use app\admin\model\ArticlesModel;
use think\Db;


class Articles extends Base
{
    /**
     *  文章列表
     * @return mixed|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function data_list(){
        $categoryModel = new ArticleCategoryModel();
        $nav = new \org\Leftnav;
        $allCategories = $categoryModel->getCategories();
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($article_id)&&$article_id!=""){
                $map['article_id'] = $article_id;
            }
            if(isset($title)&&$title!=""){
                $map['title'] = ['like',"%" . $title . "%"];
            }
            if(isset($category)&&$category!=""){
                //先判断这分类是属于第几级
                $res = DB::name('article_category')->find($category);
                if($res['pid'] == 0){
                    $map['first_category'] = $category;
                }else{
                    $res = DB::name('article_category')->find($res['pid']);
                    if($res['pid'] == 0){
                        $map['second_category'] = $category;
                    }else{
                        $map['third_category'] = $category;
                    }
                }
            }
            if(isset($status)&&$status!=""){
                $map['status'] = $status;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('articles')->where($map)->count();//计算总页面
            $articlesModel = new ArticlesModel();
            $od="article_id desc";
            $lists = $articlesModel->getDatasByWhere($map, $Nowpage, $limits,$od);
            $categories = $this->convert_arr_key($allCategories,'category_id');
            foreach ($lists as $key => $list){
                $lists[$key]['first_category'] = $lists[$key]['first_category'].'--'.$categories[$list['first_category']]['category_name_cn'];
                $lists[$key]['second_category'] = $lists[$key]['second_category'].'--'.$categories[$list['second_category']]['category_name_cn'];
//                $lists[$key]['third_category'] = $lists[$key]['third_category'].'--'.$categories[$list['third_category']]['category_name_cn'];
            }
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);

        }


        foreach($allCategories  as $key=>$vo){
            $allCategories[$key]['placeholder'] = '';
        }
        $nav->init($allCategories);
        $AllCategories = $nav->get_tree(0,'','','','','article_category');
        $this->assign('AllCategories',$AllCategories);

        return $this->fetch('articles/data_list');
    }

    public function edit_article(){
        if(request()->isPost()){
            $param = input();
//            $param['content'] = htmlspecialchars_decode($param['content']);
            $category = $param['category'];
            //判断分类是否最后一级
            $categoryInfo = DB::name('article_category')->where('pid',$category)->select();
            if(!empty($categoryInfo)){
                return json(['code' => 100, 'data' => '', 'msg' => '所选分类不是最后一级']);
            }

            //获取各级分类
            $ca_1 = DB::name('article_category')->where('category_id',$category)->find();
            if($ca_1['pid']==0){
                $param['first_category'] = $category;
            }else{
                $ca_2 = DB::name('article_category')->where('category_id',$ca_1['pid'])->find();
                if($ca_2['pid']==0){
                    $param['first_category'] = $ca_2['category_id'];
                    $param['second_category'] = $category;
                }else{
                    $ca_3 = DB::name('article_category')->where('category_id',$ca_2['pid'])->find();
                    $param['first_category'] = $ca_3['category_id'];
                    $param['second_category'] = $ca_2['category_id'];
                    $param['third_category'] = $category;
                }
            }
            unset($param['category']);
//            print_r($param);exit;

            $articlesModel = new ArticlesModel();
            $res = $articlesModel->update($param);
            if($res){
                return ['code' => 200, 'data' => '', 'msg' => '修改成功'];
            }else{
                return ['code' => 100, 'data' => '', 'msg' => '修改失败'];
            }
        }

        $categoryModel = new ArticleCategoryModel();
        $nav = new \org\Leftnav;
        $allCategories = $categoryModel->getCategories();
        foreach($allCategories  as $key=>$vo){
            $allCategories[$key]['placeholder'] = '';
        }
        $nav->init($allCategories);
        $AllCategories = $nav->get_tree(0,'','','','','article_category');
        $this->assign('AllCategories',$AllCategories);
        $article_id = input('id');
        $data = DB::name('articles')->find($article_id);
        if($data['third_category'] == 0){
            if($data['second_category'] == 0){
                $data['category'] = $data['first_category'];
            }else{
                $data['category'] = $data['second_category'];
            }
        }else{
            $data['category'] = $data['third_category'];
        }
        $this->assign('data',$data);
        return $this->fetch('articles/edit_article');
    }


    /**
     *  修改添加文章标签
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_article_tags(){
        if(request()->isPost()){
            $tags = input('tags');
            $article_id = input('article_id');
            $tag_array = explode(',',str_replace('、',',',str_replace('，',',',$tags)));
            foreach ($tag_array as $t){
                if($t != ''){
                    $t = trim($t);
                    //先检索该标签是否存在
                    $exist = Db::name('article_tags')->where('tag_name_jp',$t)->find();
                    if($exist){
                        //存在，count+1
                        Db::name('article_tags')->where('tag_id',$exist['tag_id'])->setInc('count');
                        $tag_id = $exist['tag_id'];
                    }else{
                        //不存在，新增
                        $tag_id = Db::name('article_tags')->insertGetId(['tag_name_jp'=>$t,'count'=>1]);
                    }
                    Db::name('article_tags_relative')->insert(['article_id'=>$article_id,'tag_id'=>$tag_id]);
                }
            }
            //重构keywords
            $keywords = '';
            $keyArray = Db::name('article_tags_relative')->alias('atr')
                ->join('article_tags at','at.tag_id = atr.tag_id')
                ->where('atr.article_id',$article_id)
                ->order('atr.r_id','asc')
                ->select();
            foreach ($keyArray as $key){
                $keywords = $keywords.$key['tag_name_jp'].',';
            }
            $keywords = rtrim($keywords,',');
            Db::name('articles')->where('article_id',$article_id)->update(['keywords'=>$keywords]);
            return ['code' => 200, 'data' => '', 'msg' => 'success'];


        }
        $article_id = input('id');
        $data = DB::name('articles')->find($article_id);

        //获取文章的tags
        $tags = Db::name('article_tags_relative')->alias('atr')
            ->join('article_tags at','at.tag_id = atr.tag_id')
            ->where('atr.article_id',$article_id)
            ->field('atr.r_id,at.*')
            ->select();

        $this->assign('tags',$tags);
        $this->assign('data',$data);
        return $this->fetch('articles/edit_article_tags');
    }

    /**
     * 删除文章标签
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function del_article_tag(){
        $r_id = input('r_id');
        $tag_id = input('tag_id');
        $article_id = input('article_id');
        $res = Db::name('article_tags_relative')->delete($r_id);
        if($res){
            $res_1 = Db::name('article_tags')->where('tag_id',$tag_id)->setDec('count');
            //重写文章keywords
            $keyArray = Db::name('article_tags_relative')->alias('atr')
                ->join('article_tags at','at.tag_id = atr.tag_id')
                ->where('atr.article_id',$article_id)
                ->order('atr.r_id','asc')
                ->select();
            $keywords = '';
            foreach ($keyArray as $key){
                $keywords = $keywords.$key['tag_name_jp'].',';
            }
            $keywords = rtrim($keywords,',');
            Db::name('articles')->where('article_id',$article_id)->update(['keywords'=>$keywords]);

            return ['code' => 200, 'data' => '', 'msg' => 'success'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => 'error'];
        }


    }

    /**
     * 删除文章
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del_article_data(){
        $article_id = input('id');
        $res = Db::name('articles')->delete($article_id);
        if($res){
            return ['code' => 200, 'data' => '', 'msg' => 'success'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' => 'error'];
        }
    }

    /**
     * 批量删除文章
     * @return \think\response\Json
     */
    public function batchDelArticles(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $articlesModel = new ArticlesModel();
        $flag = $articlesModel->batchDelArticles($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * [status_state 文章显示状态]
     * @return [type] [description]
     * @author
     */
    public function status_state()
    {
        extract(input());
        $articlesModel = new ArticlesModel();
        $flag = $articlesModel->statusState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 把数据的某个字段变成key
     * @param $arr
     * @param $key_name
     * @return array
     */
    private function convert_arr_key($arr, $key_name)
    {
        $arr2 = array();
        foreach ($arr as $key => $val) {
            $arr2[$val[$key_name]] = $val;
        }
        return $arr2;
    }


    public function sort(){

        if(request()->isPost()){
            $type = input('type','-1');
            $num = input('num','10');
            $tagNum = input('tagNum','40');
            if($type == '-1'){
                return json(['code' => 100, 'data' => '', 'msg' => 'Error']);
            }
            $msg = '';
            switch ($type){
                case 0:
                    $msg = '全站文章排序';
                    Db::name('articles_sort')->where('sort_type',0)->delete();
                    $res = Db::name('articles')->order('views','desc')->field('article_id')->limit($num)->select();
                    foreach ($res as $key => $re){
                        $res[$key]['sort_type'] = 0;
                        $res[$key]['category'] = 0;
                    }
                    Db::name('articles_sort')->insertAll($res);

                    break;
                case 1:
                    $msg = '一级分类文章排序';
                    Db::name('articles_sort')->where('sort_type',1)->delete();

                    $categores = Db::name('article_category')->where('pid',0)->field('category_id')->select();
                    foreach ($categores as $category)
                    {
                        $res = Db::name('articles')->where('first_category',$category['category_id'])
                            ->order('views','desc')->field('article_id')->limit($num)->select();
                        if(!empty($res)){
                            foreach ($res as $key => $re){
                                $res[$key]['sort_type'] = 1;
                                $res[$key]['category'] = $category['category_id'];
                            }
                            Db::name('articles_sort')->insertAll($res);

                        }
                    }
                    break;
                case 2:
                    $msg = '二级分类文章排序';
                    Db::name('articles_sort')->where('sort_type',2)->delete();

                    $categores = Db::name('article_category')->alias('ac2')
                        ->join('article_category ac1','ac1.category_id = ac2.pid')
                        ->where('ac2.status',1)
                        ->where('ac1.pid',0)
                        ->field('ac2.category_id')
                        ->select();
                    foreach ($categores as $category)
                    {
                        $res = Db::name('articles')->where('second_category',$category['category_id'])
                            ->order('views','desc')->field('article_id')->limit($num)->select();
                        if(!empty($res)){
                            foreach ($res as $key => $re){
                                $res[$key]['sort_type'] = 2;
                                $res[$key]['category'] = $category['category_id'];
                            }
                            Db::name('articles_sort')->insertAll($res);

                        }
                    }
                    break;
                case 3:
                    $msg = '三级分类文章排序';
                    Db::name('articles_sort')->where('sort_type',3)->delete();
                    $categores = Db::name('article_category')->alias('ac3')
                        ->join('article_category ac2','ac2.category_id = ac3.pid')
                        ->join('article_category ac1','ac1.category_id = ac2.pid')
                        ->where('ac1.pid',0)
                        ->field('ac3.category_id')
                        ->limit($num)->select();
                    foreach ($categores as $category)
                    {
                        $res = Db::name('articles')->where('third_category',$category['category_id'])
                            ->order('views','desc')->field('article_id')->limit($num)->select();
                        if(!empty($res)){
                            foreach ($res as $key => $re){
                                $res[$key]['sort_type'] = 3;
                                $res[$key]['category'] = $category['category_id'];
                            }
                            Db::name('articles_sort')->insertAll($res);

                        }
                    }
                    break;
                case 4:
                    $msg = '热门标签排序';
                    Db::name('articles_sort')->where('sort_type',4)->delete();
                    $res = Db::name('article_tags')->order('count','desc')
                        ->field('tag_id article_id')->limit($tagNum)->select();
                    foreach ($res as $key => $re){
                        $res[$key]['sort_type'] = 4;
                        $res[$key]['category'] = 0;
                    }
                    Db::name('articles_sort')->insertAll($res);

                    break;
            }

            if($msg == ''){
                return json(['code' => 100, 'data' => '', 'msg' => 'Error']);
            }
            return ['code' => 200, 'data' => '', 'msg' => $msg.'生成成功'];
        }


        return $this->fetch('articles/sort');
    }

    /**
     *  生成网站地图sitemap.xml
     * @return array|mixed
     */
    public function create_sitemap(){

        //参考https://trilltrill.jp/sitemap.xml

        if(request()->isPost()){
//            $domain = "https://www.scriptjp.com";
            $domain = config('domain');
            //一个站点支持提交的sitemap文件个数必须小于5万个，且文件大小不得超过 10MB，多于5万个后会不再处理，并显示“链接数超”的提示。
            //如果您的Sitemap超过了这些限值，请将其拆分为几个小的Sitemap。这些限制条件有助于确保您的网络服务器不会因提供大文件而超载。
            $totalLinks = 0;
            $limitLinks = 50000;
//            $per = 20;//每页20条
            $str='<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            $str.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";

            //首页
            $str.="\t<url>\r\n";
            $str.="\t\t<loc>".$domain."</loc>\r\n";
            $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
            $str.="\t\t<changefreq>".'always'."</changefreq>\r\n";
            $str.="\t</url>\r\n";
            $totalLinks++;


            //一级分类
            $firstCategories = Db::name('article_category')
                ->where('status',1)
                ->where('pid',0)
                ->order('category_id','asc')
                ->select();
            foreach ($firstCategories as $first){
                //通过该一级分类最新的文章获取更新时间
                $lastTimeArray = Db::name('articles')
                    ->where('first_category',$first['category_id'])
                    ->where('status',1)
                    ->order('create_time','desc')
                    ->limit(1)->find();
                if(!empty($lastTimeArray)){
                    $str.="\t<url>\r\n";
                    $str.="\t\t<loc>".$domain."/".$first['category_url']."/"."</loc>\r\n";
                    $str.="\t\t<lastmod>".date('Y-m-d',$lastTimeArray['create_time'])."</lastmod>\r\n";
                    $str.="\t\t<changefreq>".'daily'."</changefreq>\r\n";
                    $str.="\t</url>\r\n";
                    $totalLinks++;
                }
            }

            //自定义一级分类，例如工具首页
            $str.="\t<url>\r\n";
            $str.="\t\t<loc>".$domain."/tools/"."</loc>\r\n";
//        $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
//        $str.="\t\t<changefreq>".'weekly'."</changefreq>\r\n";
            $str.="\t</url>\r\n";
            $totalLinks++;


            //二级分类
            $secondCategories = Db::name('article_category')->alias('second')
                ->join('article_category first','second.pid = first.category_id')
                ->where('second.status',1)
                ->where('first.pid',0)
                ->order('second.category_id','asc')
                ->field('second.*,first.category_url first_category_url')
                ->select();
            foreach ($secondCategories as $second){
                //通过该二级分类最新的文章获取更新时间
                $lastTimeArray = Db::name('articles')
                    ->where('second_category',$second['category_id'])
                    ->where('status',1)
                    ->order('create_time','desc')
                    ->limit(1)->find();
                if(!empty($lastTimeArray)){
                    $str.="\t<url>\r\n";
                    $str.="\t\t<loc>".$domain."/".$second['first_category_url']."/list_".$second['category_id']."_1.html"."</loc>\r\n";
                    $str.="\t\t<lastmod>".date('Y-m-d',$lastTimeArray['create_time'])."</lastmod>\r\n";
                    $str.="\t\t<changefreq>".'daily'."</changefreq>\r\n";
                    $str.="\t</url>\r\n";
                    $totalLinks++;
                }
            }

            //工具页
            $tools = config('tools');
            foreach ($tools as $tool){
                $str.="\t<url>\r\n";
                $str.="\t\t<loc>".$domain."/tools/".$tool['action'].".html"."</loc>\r\n";
//        $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
//        $str.="\t\t<changefreq>".'weekly'."</changefreq>\r\n";
                $str.="\t</url>\r\n";
                $totalLinks++;
            }

            //文章页
            $articles = Db::name('articles')
                ->where('status',1)
                ->order('article_id','desc')
                ->select();
            foreach ($articles as $article){
                $str.="\t<url>\r\n";
                $str.="\t\t<loc>".$domain."/article/".$article['article_id'].".html"."</loc>\r\n";
                $str.="\t\t<lastmod>".date('Y-m-d',$article['create_time'])."</lastmod>\r\n";
                $str.="\t\t<changefreq>".'monthly'."</changefreq>\r\n";
                $str.="\t</url>\r\n";
                $totalLinks++;
            }

            $str.='</urlset>';

            //在根目录生成sitemap.xml
            $file_url = fopen($_SERVER['DOCUMENT_ROOT'].'/sitemap.xml', "w+");
            fwrite($file_url, $str);
            fclose($file_url);

            if($limitLinks < $totalLinks){
                $msg = "网站地图生成成功，但sitemap.xml链接数大于5万个，请注意！";
            }else{
                $msg = '网站地图生成成功';
            }


            return ['code' => 200, 'data' => '', 'msg' => $msg];
        }

        return $this->fetch('articles/create_sitemap');
    }

    public function test(){
//        $d = $_SERVER['DOCUMENT_ROOT'];
//        echo $d;
//        exit;

        //一个站点支持提交的sitemap文件个数必须小于5万个，且文件大小不得超过 10MB，多于5万个后会不再处理，并显示“链接数超”的提示。
        //如果您的Sitemap超过了这些限值，请将其拆分为几个小的Sitemap。这些限制条件有助于确保您的网络服务器不会因提供大文件而超载。
        $totalLinks = 0;
        $limitLinks = 50000;
        $per = 20;//每页20条
        $domain = config('domain');
        $str='<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
        $str.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";

        //首页
        $str.="\t<url>\r\n";
        $str.="\t\t<loc>".$domain."</loc>\r\n";
        $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
        $str.="\t\t<changefreq>".'always'."</changefreq>\r\n";
        $str.="\t</url>\r\n";
        $totalLinks++;


        //一级分类
        $firstCategories = Db::name('article_category')
            ->where('status',1)
            ->where('pid',0)
            ->order('category_id','asc')
            ->select();
        foreach ($firstCategories as $first){
            //通过该一级分类最新的文章获取更新时间
            $lastTimeArray = Db::name('articles')
                ->where('first_category',$first['category_id'])
                ->where('status',1)
                ->order('create_time','desc')
                ->limit(1)->find();
            if(!empty($lastTimeArray)){
                $str.="\t<url>\r\n";
                $str.="\t\t<loc>".$domain."/aaa/index_".$first['category_id'].".html"."</loc>\r\n";
                $str.="\t\t<lastmod>".date('Y-m-d',$lastTimeArray['create_time'])."</lastmod>\r\n";
                $str.="\t\t<changefreq>".'daily'."</changefreq>\r\n";
                $str.="\t</url>\r\n";
                $totalLinks++;
            }
        }

        //自定义一级分类，例如工具首页
        $str.="\t<url>\r\n";
        $str.="\t\t<loc>".$domain."/tools/"."</loc>\r\n";
//        $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
//        $str.="\t\t<changefreq>".'weekly'."</changefreq>\r\n";
        $str.="\t</url>\r\n";
        $totalLinks++;


        //二级分类
        $secondCategories = Db::name('article_category')->alias('second')
            ->join('article_category first','second.pid = first.category_id')
            ->where('second.status',1)
            ->where('first.pid',0)
            ->order('second.category_id','asc')
            ->field('second.*')
            ->select();
        foreach ($secondCategories as $second){
            //通过该二级分类最新的文章获取更新时间
            $lastTimeArray = Db::name('articles')
                ->where('second_category',$second['category_id'])
                ->where('status',1)
                ->order('create_time','desc')
                ->limit(1)->find();
            if(!empty($lastTimeArray)){
                $str.="\t<url>\r\n";
                $str.="\t\t<loc>".$domain."/lists/list_".$second['category_id'].".html"."</loc>\r\n";
                $str.="\t\t<lastmod>".date('Y-m-d',$lastTimeArray['create_time'])."</lastmod>\r\n";
                $str.="\t\t<changefreq>".'daily'."</changefreq>\r\n";
                $str.="\t</url>\r\n";
                $totalLinks++;
            }
        }

        //工具页
        $tools = config('tools');
        foreach ($tools as $tool){
            $str.="\t<url>\r\n";
            $str.="\t\t<loc>".$domain."/tools/".$tool['action'].".html"."</loc>\r\n";
//        $str.="\t\t<lastmod>".date('Y-m-d')."</lastmod>\r\n";
//        $str.="\t\t<changefreq>".'weekly'."</changefreq>\r\n";
            $str.="\t</url>\r\n";
            $totalLinks++;
        }

        //文章页
        $articles = Db::name('articles')
            ->where('status',1)
            ->order('article_id','desc')
            ->select();
        foreach ($articles as $article){
            $str.="\t<url>\r\n";
            $str.="\t\t<loc>".$domain."/article/".$article['article_id'].".html"."</loc>\r\n";
            $str.="\t\t<lastmod>".date('Y-m-d',$article['create_time'])."</lastmod>\r\n";
            $str.="\t\t<changefreq>".'monthly'."</changefreq>\r\n";
            $str.="\t</url>\r\n";
            $totalLinks++;
        }


        $str.='</urlset>';
        echo $str;
    }

}