<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    public static function getArticleCategory($hidden_del=true){
        $options = [
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
          [['name','intro','sort','status'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }
}