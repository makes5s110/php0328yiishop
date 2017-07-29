<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    //记住，自动登录
    public $remember;
    //验证码
    public $code;


    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['code', 'captcha', 'captchaAction' => 'user/captcha'],
            ['remember','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code' => '验证码',
            'remember'=>'记住我'
        ];
    }

    public function login()
    {
        // 通过用户名查找用户
        $model = User::findOne(['username' => $this->username]);
        //判断是否存在该用户

        if ($model) {
            //验证输入的密码和数据库中的密码是否一致
//            var_dump($model->password_hash,$this->password);exit;
//            var_dump(\Yii::$app->security->validatePassword($this->password,$model->password_hash));exit;
            if (\Yii::$app->security->validatePassword($this->password,$model->password_hash)) {

                //密码正确.可以登录
                //2 登录(保存用户信息到session)
                \Yii::$app->user->login($model,$this->remember?3600*24:0);
                return true;
            } else {
                //提示密码错误信息
                $this->addError('password', '密码错误');
            }
        } else {
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username', '用户名不存在');
        }
        return false;
    }
}
