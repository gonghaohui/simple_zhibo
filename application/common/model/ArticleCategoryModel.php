<?php

namespace app\common\model;
use think\Model;
use think\Db;

class ArticleCategoryModel extends Model
{
    protected $name = 'article_category';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    /**
     * [getCategories 获取全部分类]
     * @author
     */
    public function getCategories()
    {
        return $this->order('category_id asc')->select()->toArray();
    }

    /**
     * [getAllCategories 获取全部文章分类]
     * @author
     */
    public function getAllCategories($map,$Nowpage,$limits)
    {
        return $this->where($map)->page($Nowpage,$limits)->order('category_id asc')->select()->toArray();
    }

    /**
     * [getFirstCategoryArticlesAttr 获取一级分类下的12篇文章]
     * @author
     */
    public function getFirstCategoryArticlesAttr($value,$data){
        $datas = Db::name('articles')
            ->where('first_category',$data['category_id'])
            ->where('status',1)
            ->limit(12)
            ->order('article_id','desc')
            ->select();

        return $datas;

    }

    public function getSecondCategoryArticlesAttr($value,$data){
        $datas = Db::name('articles')
            ->where('second_category',$data['category_id'])
            ->where('status',1)
            ->limit(8)
            ->order('article_id','desc')
            ->select();

        return $datas;
    }


}