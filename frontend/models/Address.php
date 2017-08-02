<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord
{

    public function rules()
    {
        return [
            [['name','tel','address','province','city','area'],'required'],
            ['status','safe']

        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'收货人',
            'address'=>'详细地址',
            'tel'=>'电话'
        ];
    }
    public static function getName(){

    }
}