<?php
namespace backend\models;

use yii\base\Model;

class ChangeForm extends Model{
    public $old_password;//旧密码
    public $re_password;//确认密码
    public $new_password;//新密码
    public function rules()
    {
        return [
            [['old_password','new_password','re_password'],'required'],
            ['re_password','compare','compareAttribute'=>'new_password','message'=>'两次输入的密码不一致，请确认后重新输入'],
            ['old_password','compare','compareAttribute'=>'new_password','operator'=>'!=','message'=>'旧密码不能和新密码一致，请确认后重新输入'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            're_password'=>'确认密码'
        ];
    }
    public function change(){
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->security->validatePassword($this->old_password,$user->password_hash)){
            //密码一致
            return true;
        }
        else{
            //提示用户密码输入错误
            $this->addError('old_password', '输入的密码与原密码不一致');
        }
    }
}