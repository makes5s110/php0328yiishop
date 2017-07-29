<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $sort
 * @property string $url
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function getMenuOptions()
    {
        return ArrayHelper::merge([''=>'请选择菜单',0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','name'));

    }
    public static function getUrlOptions(){
        return ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name');

    }
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','required'],
            [['parent_id', 'sort'], 'integer'],
            [['name', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '父ID',
            'sort' => '排序',
            'url' => '路由',
        ];
    }
}
