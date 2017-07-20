<?php

namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Article extends ActiveRecord{


    public static function getArticleOptions($hidden_del=true){
        $options=[
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($options[-1]);
        }
        return $options;
    }
    public function rules()
    {
        return [
            [['name','intro','sort','status','article_category_id'],'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'article_category_id'=>'文章分类ID',
            'status'=>'状态',
        ];
    }
    //获取文章分类
    public static function getArticle(){
        return ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
    }
    //建立与文章分类一对一关系
    public function getArticleCategory()
    {
       return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);

    }
}