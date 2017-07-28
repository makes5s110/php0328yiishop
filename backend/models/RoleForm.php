<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];
    const SCENARIO_ADD = 'add';
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            //角色名不能重复
            ['name','validateName','on'=>self::SCENARIO_ADD]
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名字',
            'description'=>'描述',
            'permissions'=>'权限名',
        ];
    }
    //判断角色名字不能重复
    public function validateName(){
        //获取角色名称
        $role = \Yii::$app->authManager->getRole($this->name);
        if($role){
            $this->addError('name','该角色名称已存在，请重新填写');
        }
    }
}