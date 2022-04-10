<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class ArticleTagsModel extends Model
{
    protected $name = 'article_tags';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    /**
     * 根据搜索条件获取标签列表信息
     * @author
     */
    public function getDatasByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }


}