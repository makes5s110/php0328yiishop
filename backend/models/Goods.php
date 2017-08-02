<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['brand_id', 'stock', 'is_on_sale',  'sort', 'market_price', 'shop_price','name'], 'required'],
            [['market_price', 'shop_price'], 'match','pattern'=>'/^\d+\.\d{2}$/','message'=>'输入的价格不合理,如:0.00'],
            [['name'], 'string', 'max' => 50],
            [['sn'], 'string', 'max' => 30],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态(1正常 0回收站)',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }
    //获取品牌分类
    public static function getBrandInfo(){
        return ArrayHelper::map(Brand::find()->all(),'id','name');
    }
    //建立与品牌分类一对一关系
    public function getBrand()
    {
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public static function getOptions(){
        return[
            1=>'在售',0=>'下架'
        ];
    }
    /*
         * 商品和相册关系 1对多
         */
    public function getGalleries()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }

    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
}
