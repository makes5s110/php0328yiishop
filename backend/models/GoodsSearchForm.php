<?php
namespace backend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model{

    public $name;
    public $sn;
    public $maxPrice;
    public $minPrice;

    public function search(ActiveQuery $query){
        //加载模型数据
         $this->load(\Yii::$app->request->get());

        //判断是否传入数据
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','shop_price',$this->minPrice]);
        }
        if($this->maxPrice){
            $query->andWhere(['<=','shop_price',$this->maxPrice]);
        }
    }

    public function rules()
    {
        return [
            [['name','sn','maxPrice','minPrice'],'safe']
        ];
    }
}