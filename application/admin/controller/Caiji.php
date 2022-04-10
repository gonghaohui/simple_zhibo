<?php

namespace app\admin\controller;
use app\admin\model\CaijiDatasModel;
use app\admin\model\ArticleCategoryModel;
use QL\QueryList;//php版本大于等于7.2不可用
use think\Db;
use think\Session;

class Caiji extends Base
{

    /**
     * data_list 采集后数据的列表
     * @return mixed|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function data_list(){
        $categoryModel = new ArticleCategoryModel();
        $nav = new \org\Leftnav;
        $allCategories = $categoryModel->getShowCategories();

        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($caiji_id)&&$caiji_id!=""){
                $map['caiji_id'] = $caiji_id;
            }
            if(isset($title_cn)&&$title_cn!=""){
                $map['title_cn'] = ['like',"%" . $title_cn . "%"];
            }
            if(isset($action)){
                Session::set('cat_id',$category);
            }else{
                $category = Session::get('cat_id');
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
            if(isset($caiji_images)&&$caiji_images!=""){
                $map['caiji_images'] = $caiji_images;
            }
            if(isset($publish)&&$publish!=""){
                $map['publish'] = $publish;
            }else {
                $map['publish'] = 0;
            }

            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('caiji_datas')->where($map)->count();//计算总页面
            $CaijiModel = new CaijiDatasModel();
            $od="caiji_id desc";
            $lists = $CaijiModel->getDatasByWhere($map, $Nowpage, $limits,$od);

//            print_r($lists);exit;
            $caijiWeb = config('caiji_webs');
            $categories = $this->convert_arr_key($allCategories,'category_id');
            foreach ($lists as $key => $list){
                $lists[$key]['web_whole_url'] = 'http://'.$caijiWeb[$list['web_type']]['site'].$list['web_url'];
                $lists[$key]['web_type'] = $caijiWeb[$list['web_type']]['name'];
                $lists[$key]['first_category'] = $lists[$key]['first_category'].'--'.$categories[$list['first_category']]['category_name_cn'];
                $lists[$key]['second_category'] = $lists[$key]['second_category'].'--'.$categories[$list['second_category']]['category_name_cn'];
//                $lists[$key]['third_category'] = $lists[$key]['third_category'].'--'.$categories[$list['third_category']]['category_name_cn'];
                $lists[$key]['total'] = mb_strlen($list['content_cn']);
            }
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }


        foreach($allCategories  as $key=>$vo){
            $allCategories[$key]['placeholder'] = '';
        }
        $nav->init($allCategories);
        $AllCategories = $nav->get_tree(0,'','','','','article_category');
        $this->assign('AllCategories',$AllCategories);
        $this->assign('cat_id',Session::get('cat_id'));
        return $this->fetch('caiji/data_list');
    }

    public function data_ready_list(){

        $categoryModel = new ArticleCategoryModel();
        $nav = new \org\Leftnav;
        $allCategories = $categoryModel->getCategories();

        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($caiji_id)&&$caiji_id!=""){
                $map['caiji_id'] = $caiji_id;
            }
            if(isset($title_cn)&&$title_cn!=""){
                $map['title_cn'] = ['like',"%" . $title_cn . "%"];
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
            if(isset($caiji_images)&&$caiji_images!=""){
                $map['caiji_images'] = $caiji_images;
            }
            $map['publish'] = 3;

            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $count = Db::name('caiji_datas')->where($map)->count();//计算总页面
            $CaijiModel = new CaijiDatasModel();
            $od="caiji_id desc";
            $lists = $CaijiModel->getDatasByWhere($map, $Nowpage, $limits,$od);

            $caijiWeb = config('caiji_webs');
            $categories = $this->convert_arr_key($allCategories,'category_id');
            foreach ($lists as $key => $list){
                $lists[$key]['web_type'] = $caijiWeb[$list['web_type']]['name'];
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
        return $this->fetch('caiji/data_ready_list');

    }

    public function ready_publish_article(){
        $id = input('id');
        $data = Db::name('caiji_datas')->where('caiji_id',$id)->find();
        if(empty($data)){
            return json(['code' => 100, 'data' => '', 'msg' => '导入到准备发布列表的数据不存在']);
        }

        if($data['first_category'] == '' or $data['title_jp'] == '' or $data['intro_jp'] == '' or $data['content_jp'] == '')
        {
            return json(['code' => 100, 'data' => '', 'msg' => '导入到准备发布列表的数据不完整，请先修改']);
        }
        if($data['caiji_images'] == 0){
            return json(['code' => 100, 'data' => '', 'msg' => '请先下载图片再导入到准备发布列表']);
        }
        if($data['publish'] == 1 or $data['publish'] == 2){
            return json(['code' => 100, 'data' => '', 'msg' => '该数据已发布或者已删除了']);
        }

        $res = Db::name('caiji_datas')->where('caiji_id',$id)->update(['publish'=>3]);
        if($res){
            return json(['code' => 200, 'data' => '', 'msg' => '数据导入到准备发布列表成功']);
        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '数据导入到准备发布列表失败']);
        }

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


    /**
     * edit_caiji_data 修改采集的数据
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_caiji_data(){
        if(request()->isPost()){
            $param = input();
            $type = input('type','');
//            print_r($param);exit;
            $caijiDatasModel = new CaijiDatasModel();
//            $param['content_jp'] = htmlspecialchars_decode($param['content_jp']);
//            $param['content_jp'] = $param['content_jp'];
            unset($param['type']);
            $res = $caijiDatasModel->update($param);
            if($res){
                return ['code' => 200, 'data' => $type, 'msg' => '修改成功'];
            }else{
                return ['code' => 100, 'data' => $type, 'msg' => '修改失败'];
            }
        }

        $caiji_id = input('id');
        $type = input('type');
        $data = DB::name('caiji_datas')->find($caiji_id);
//        print_r($data);
//        $data['content_jp'] = htmlspecialchars($data['content_jp']);
        $this->assign('data',$data);
        $this->assign('type',$type);
        return $this->fetch('caiji/edit_caiji_data');
    }


    /**
     * 删除采集数据
     * @return \think\response\Json
     */
    public function del_caiji_data(){
        $id = input('param.id');
        $caijiModel = new CaijiDatasModel();
        $flag = $caijiModel->delBeforeCheck($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 恢复采集数据
     * @return \think\response\Json
     */
    public function restore_caiji_data(){
        $id = input('param.id');
        $caijiModel = new CaijiDatasModel();
        $flag = $caijiModel->restoreBeforeCheck($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     *  采集数据
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caiji_datas(){

        if(request()->isPost()){
            $web_type = input('web_type');
            $category = input('category');

            $url      = input('url');
            //判断分类是否最后一级
            $categoryInfo = DB::name('article_category')->where('pid',$category)->select();
            if(!empty($categoryInfo)){
                return json(['code' => 100, 'data' => '', 'msg' => '所选分类不是最后一级']);
            }
            if($web_type == 1){
                $flag = $this->caiji_scripthome($web_type,$category,$url);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }


            return json(['code' => 100, 'data' => '', 'msg' => 'error']);
        }


        $sites = config('caiji_webs');
        $categoryModel = new ArticleCategoryModel();
        $nav = new \org\Leftnav;
        $allCategories = $categoryModel->getShowCategories();
        foreach($allCategories  as $key=>$vo){
            $allCategories[$key]['placeholder'] = '';
        }
        $nav->init($allCategories);
        $AllCategories = $nav->get_tree(0,'','','','','article_category');
        $this->assign('AllCategories',$AllCategories);
        $this->assign('sites',$sites);
        return $this->fetch('caiji/caiji_datas');
    }


    private function caiji_scripthome($web_type,$category,$url){
        $base_url = 'https://www.jb51.net';
        // 采集规则
        $rules = [
            // 文章标题
            'title'    => ['#main .artlist>dl>dt>a','text'],
            // url
            'data_url' => ['#main .artlist>dl>dt>a','href'],

        ];


        $rules_1 = [
            // 文章标题
            'title'    => ['#lists .item-inner .rbox .rbox-inner>p .link','text'],
            // url
            'data_url' => ['#lists .item-inner .rbox .rbox-inner>p .link','href'],

        ];


        $data_lists = QueryList::Query($url,$rules,'','UTF-8','')->data;
        if(empty($data_lists)){
            $data_lists = QueryList::Query($url,$rules_1,'','UTF-8','')->data;
            if(empty($data_lists)){
                return ['code' => 100, 'data' => '', 'msg' => '采集的列表数据为空！'];
            }

        }
//        print_r($data_lists);exit;
        // 内容采集规则
        $content_rules = [
            // 文章标题
            'title'    => ['#article>h1','text'],
            'intro_cn' => ['#article .summary','text'],
            'content'  => ['#content','html','-.art_xg'],
            'tags'     => ['.meta-tags>li>a','text']
        ];

        //总条数
        $total_caiji_num = count($data_lists);
        //已在数据库中存在的链接数，不采集
        $total_exist_num = 0;
        //采集成功数
        $total_insert_num = 0;
        //采集失败数
        $total_notinsert_num = 0;
        //因为采集不到数据统计
        $total_empty_num = 0;

        $first_category = 0;
        $second_category = 0;
        $third_category = 0;
        //获取各级分类
        $ca_1 = DB::name('article_category')->where('category_id',$category)->find();
        if($ca_1['pid']==0){
            $first_category = $category;
        }else{
            $ca_2 = DB::name('article_category')->where('category_id',$ca_1['pid'])->find();
            if($ca_2['pid']==0){
                $first_category = $ca_2['category_id'];
                $second_category = $category;
            }else{
                $ca_3 = DB::name('article_category')->where('category_id',$ca_2['pid'])->find();
                $first_category = $ca_3['category_id'];
                $second_category = $ca_2['category_id'];
                $third_category = $category;
            }
        }


        foreach ($data_lists as $list){
            $dataInsert = array();
            //先判断该链接是否已经采集过
            $re = Db::name('caiji_datas')
                ->where('web_type',$web_type)->where('web_url',$list['data_url'])
                ->find();
            if(!empty($re)){
                $total_exist_num++;
                //数据库里有，不采集
            }else{
                //数据库里没有，则采集
                $real_url = $base_url.$list['data_url'];
                $data = QueryList::Query($real_url,$content_rules,'','UTF-8','')->data;
//                print_r($data);exit;
                if(empty($data)){
                    $total_empty_num++;
                    $total_insert_num++;
                }else{
                    $dataInsert['web_type'] = $web_type;
                    $dataInsert['web_url'] = $list['data_url'];
                    $dataInsert['first_category'] = $first_category;
                    $dataInsert['second_category'] = $second_category;
                    $dataInsert['third_category'] = $third_category;
                    $dataInsert['title_cn'] = $data[0]['title'];
                    $dataInsert['intro_cn'] = $data[0]['intro_cn'];
                    $new_content = str_replace('src="//img.jbzj.com','src="https://img.jbzj.com',$data[0]['content']);
                    $new_content = str_replace('class="jb51code"','class="code"',$new_content);
                    $new_content = str_replace('class="maodian"','class="anchor-point"',$new_content);
                    $new_content = str_replace('脚本之家',' コードスクリプトの家 ',$new_content);
                    $dataInsert['content_cn'] = $new_content;
                    $dataInsert['tags_cn'] = '';
                    foreach ($data as $d){
                        if(isset($d['tags'])){
                            $dataInsert['tags_cn'] = $dataInsert['tags_cn'].$d['tags'].',';
                        }
                    }
                    $dataInsert['tags_cn'] = rtrim($dataInsert['tags_cn'],',');
                    $dataInsert['create_time'] = time();
                    $dataInsert['update_time'] = time();

                    //判断文章内容里是否有图片
                    preg_match_all('#(<img[^>])([^>]+>)#i',$dataInsert['content_cn'],$imgArray);
                    if(empty($imgArray[0])){
                        $dataInsert['caiji_images'] = 1;//已下载或者没有图片不需要下载
                    }
                    // 过滤掉emoji表情
                    $dataInsert['content_cn'] = $this->filter_Emoji($dataInsert['content_cn']);
//                    print_r($dataInsert);exit;
                    $r = Db::name('caiji_datas')->insert($dataInsert);
                    if($r){
                        $total_insert_num++;
                    }else{
                        $total_notinsert_num++;
                    }
                }


            }
        }
        return ['code' => 200, 'data' => '', 'msg' => '列表总条数：'.$total_caiji_num.'---重复数：'.$total_exist_num.'---入库成功：'.$total_insert_num.'---入库失败：'.$total_notinsert_num.'---采集数据为空的统计'.$total_empty_num];
    }

    // 过滤掉emoji表情
    private function filter_Emoji($str)
    {
        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }

    /**
     * 下载图片
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function download_images(){
        $id = input('id');
        $data = Db::name('caiji_datas')->where('caiji_id',$id)->find();
        if(empty($data)){
            return json(['code' => 100, 'data' => '', 'msg' => '数据不存在']);
        }
        $content = $data['content_cn'];

        //先判断有没有图片下载
        preg_match_all('#(<img[^>])([^>]+>)#i',$content,$imgArray);
        if(empty($imgArray[0])){
            Db::name('caiji_datas')->where('caiji_id',$id)->update(['caiji_images'=>1]);
            return json(['code' => 200, 'data' => '', 'msg' => '内容没有图片下载']);
        }


//        echo $content;
        $con =<<<STR
$content
STR;
        $html = QueryList::run('DImage',[
            //html内容
            'content' => $con,
            //图片保存路径（相对于网站跟目录），可选，默认:/images
            'image_path' => '/img/'.date('Ymd'),
            //网站根目录全路径，如:/var/www/html
            'www_root' => ROOT_PATH.'public/',
//            'www_root' => dirname(ROOT_PATH),
            //补全HTML中的图片路径,可选，默认为空
            'base_url' => '',
            //图片链接所在的img属性，可选，默认src
            //多个值的时候用数组表示，越靠前的属性优先级越高
            'attr' => array('src'),
            //单个值时可直接用字符串
            //'attr' => 'data-src',
            //回调函数，用于对图片的额外处理，可选，参数为img的phpQuery对象
            'callback' => function($imgObj){
                $imgObj->removeAttr('class');
            }
        ]);

        $res = DB::name('caiji_datas')->where('caiji_id',$id)->update(['content_cn'=>$html]);
        if($res){
            Db::name('caiji_datas')->where('caiji_id',$id)->update(['caiji_images'=>1]);
            return json(['code' => 200, 'data' => '', 'msg' => '下载图片成功']);

        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '下载图片失败2']);
        }

    }


    public function publish_article(){
        $id = input('id');
        $data = Db::name('caiji_datas')->where('caiji_id',$id)->find();
        if(empty($data)){
            return json(['code' => 100, 'data' => '', 'msg' => '发布的数据不存在']);
        }

        if($data['first_category'] == '' or $data['title_jp'] == '' or $data['intro_jp'] == '' or $data['content_jp'] == '')
        {
            return json(['code' => 100, 'data' => '', 'msg' => '发布的数据不完整，请先修改']);
        }
        if($data['caiji_images'] == 0){
            return json(['code' => 100, 'data' => '', 'msg' => '请先下载图片再发布']);
        }
        if($data['publish'] == 1 or $data['publish'] == 2){
            return json(['code' => 100, 'data' => '', 'msg' => '该数据已发布或者已删除了']);
        }

        $res = Db::name('articles')->where('caiji_id',$data['caiji_id'])->find();
        if(!empty($res)){
            return json(['code' => 100, 'data' => '', 'msg' => '该数据已发布过了']);
        }

        $inputData = array();
        $inputData['first_category']  = $data['first_category'];
        $inputData['second_category'] = $data['second_category'];
        $inputData['third_category']  = $data['third_category'];
        $inputData['title']           = $data['title_jp'];
        $inputData['author']          = $data['author_jp'];
        $inputData['description']     = $data['intro_jp'];
        $inputData['content']         = $data['content_jp'];
        $inputData['keywords']        = $data['tags_jp'];
        $inputData['status']          = 1;
        $inputData['views']           = rand(100,999);
        $inputData['caiji_id']        = $data['caiji_id'];
        $inputData['create_time']     = time();
        $inputData['update_time']     = time();
//        print_r($inputData);exit;

        $article_id = DB::name('articles')->insertGetId($inputData);
        if($article_id){
            DB::name('caiji_datas')->where('caiji_id',$data['caiji_id'])->update(['publish'=>1]);
            //处理tags
            if($data['tags_jp'] != ''){
                $tags_array = explode(',',str_replace('、',',',str_replace('，',',',$data['tags_jp'])));
                foreach ($tags_array as $tag){
                    if($tag != ''){
                        $tagData = Db::name('article_tags')->where('tag_name_jp',$tag)->find();
                        if(empty($tagData)){
                            //不存在，插入数据库获取tag_id
                            $tag_id = Db::name('article_tags')->insertGetId(['tag_name_jp'=>$tag,'count'=>1]);
                            if($tag_id){
                                Db::name('article_tags_relative')->insert(['article_id'=>$article_id,'tag_id'=>$tag_id]);
                            }
                        }else{
                            //存在，该标签count+1
                            $tag_id = $tagData['tag_id'];
                            Db::name('article_tags')->where('tag_id',$tag_id)->setInc('count');
                            Db::name('article_tags_relative')->insert(['article_id'=>$article_id,'tag_id'=>$tag_id]);
                        }

                    }
                }

            }
            return json(['code' => 200, 'data' => '', 'msg' => '数据发布成功']);

        }else{
            return json(['code' => 100, 'data' => '', 'msg' => '该数据已发布失败']);
        }

    }





    //------------------------------------------------------------------------------------------------------------------------

    public function test(){

        $url = 'https://www.jb51.net/article/4379.htm';
        // 采集规则
        $rules = [
            // 文章标题
            'title'    => ['#article>h1','text'],
            // 简介
            'intro_cn' => ['#article .summary','text'],
            // 文章内容---去掉class为art_xg的内容
            'content'  => ['#content','html','-.art_xg'],
            'tags'     => ['.meta-tags>li>a','text']
        ];


        $data = QueryList::Query($url,$rules,'','UTF-8','GBK')->data;
        echo $data[0]['content'];exit;

        $data[0]['content'] = str_replace('//img','https://img',$data[0]['content']);
        print_r($data);

        exit;
        $con =<<<STR
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
$content
STR;


        $html = QueryList::run('DImage',[
            //html内容
            'content' => $con,
            //图片保存路径（相对于网站跟目录），可选，默认:/images
            'image_path' => '/img/'.date('Ymd'),
            //网站根目录全路径，如:/var/www/html
            'www_root' => ROOT_PATH.'public/',
//            'www_root' => dirname(ROOT_PATH),
            //补全HTML中的图片路径,可选，默认为空
            'base_url' => '',
            //图片链接所在的img属性，可选，默认src
            //多个值的时候用数组表示，越靠前的属性优先级越高
            'attr' => array('src'),
            //单个值时可直接用字符串
            //'attr' => 'data-src',
            //回调函数，用于对图片的额外处理，可选，参数为img的phpQuery对象
            'callback' => function($imgObj){
//                $imgObj->attr('alt','xxx');
                $imgObj->removeAttr('class');
                //......
            }
        ]);

        print_r($html);

    }

    public function download(){
//        echo ROOT_PATH;exit;

        $html = QueryList::run('DImage',[
            //html内容
            'content' => file_get_contents('http://test.com/1.html'),
            //图片保存路径（相对于网站跟目录），可选，默认:/images
            'image_path' => '/img/'.date('Ymd'),
            //网站根目录全路径，如:/var/www/html
            'www_root' => ROOT_PATH.'public/',
//            'www_root' => dirname(ROOT_PATH),
            //补全HTML中的图片路径,可选，默认为空
            'base_url' => '',
            //图片链接所在的img属性，可选，默认src
            //多个值的时候用数组表示，越靠前的属性优先级越高
            'attr' => array('src'),
            //单个值时可直接用字符串
            //'attr' => 'data-src',
            //回调函数，用于对图片的额外处理，可选，参数为img的phpQuery对象
            'callback' => function($imgObj){
//                $imgObj->attr('alt','xxx');
                $imgObj->removeAttr('class');
                //......
            }
        ]);

        print_r($html);



    }

    public function download_single_img(){
        $url = 'https://img.jbzj.com/file_images/article/202107/2021715143527264.png?2021615143536';

        $img = $this->getImage($url,"img/");

    }
    private function getImage($url,$save_dir='',$filename='',$type=0){
        if(trim($url)==''){
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./';
        }
        if(trim($filename)==''){//保存文件名
            $ext=strrchr($url,'.');
        if($ext!='.gif'&&$ext!='.jpg'&&$ext!='.png'){
//            return array('file_name'=>'','save_path'=>'','error'=>3);
            echo "error";exit;
        }
        $filename=time().$ext;
//            $filename=time().'.png';
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }
        //获取远程文件所采用的方法
        if($type){
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img=ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2=@fopen($save_dir.$filename,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);

    }

    /**
     * batchDelUser 批量删除采集数据
     * @return \think\response\Json
     */
    public function batchDelCaijiDatas(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $ids = explode(',',$ids);

        $caijiModel = new CaijiDatasModel();
        $flag = $caijiModel->batchDelCaijiDatas($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);

    }



}