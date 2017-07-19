<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    public $imgFile;
    public static function getStatusOptions($hidden_del=true){
        $options = [
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;

    }
    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required'],
            //图片验证规则，extensions表示后缀
            ['imgFile','file','extensions'=>['jpg','bng','gif']]
        ];

    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'imgFile'=>'LOGO图片',
            'status'=>'状态'
        ];
    }
}