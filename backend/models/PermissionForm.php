<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{

    public $name;//权限名称
    public $description;//权限描述
    const SCENARIO_ADD = 'add';
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD]
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述'
        ];
    }
    //验证权限名不能重复
    public function validateName(){
        //实例化权限组件
        $authManager = \Yii::$app->authManager;
        //查询权限名称(传入需要查询的权限名称)
        //没有查询到就返回null
        if($authManager->getPermission($this->name)){
           $this->addError('name','输入的权限名已存在，请重新输入');
        }
    }
}