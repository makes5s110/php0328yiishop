<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use backend\models\ChangeForm;
use yii\data\Pagination;
use yii\captcha\CaptchaAction;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    //展示
    public function actionIndex()
    {
        $query = User::find()->where('status=1');
        //总条数
        $total = $query->count();
        //每页显示的条数
        $PageSize = 3;
        //实例化一个分页工具类
        $page = new Pagination(
            [
            //设置默认参数
            'totalCount'=>$total,
            'defaultPageSize'=>$PageSize
            ]
        );
        //查找所有的用户
        $users = $query->limit($page->limit)->offset($page->offset)->all();
        //调用视图展示页面
        return $this->render('index',['users'=>$users,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        //实例化用户模型
        $user = new User(['scenario'=>User::SCENARIO_ADD]);
        //实例化组件
        $request = new Request();
        //判断是否是post提交
        if($request->isPost){
            //获取用户输入的数据
            $user->load($request->post());

            //验证数据的有效性
            if($user->validate()){
                $user->status = 1;
                $user->created_at = time();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                //把用户输入的密码加密
                $user->password_hash =\Yii::$app->security->generatePasswordHash($user->password_hash);
                //保存数据
                $user->save(false);

            }
            \Yii::$app->session->setFlash('success','保存成功');
            //验证成功，跳转页面
            return $this->redirect(['user/index']);
        }

        //跳转页面,并传值
       return $this->render('add',['user'=>$user]);
    }
    //修改
    public function actionEdit($id){
        $user = User::findOne(['id'=>$id]);

        //实例化组件
        $request = new Request();
        //判断是否是post提交
        if($request->isPost){
            //获取用户输入的数据
            $user->load($request->post());
            //验证数据的有效性
            if($user->validate()){
                $user->updated_at = time();
                //把用户输入的密码加密
                $user->password_hash =\Yii::$app->security->generatePasswordHash($user->password_hash);
                //保存数据
                $user->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
            }
            //验证成功，跳转页面
            return $this->redirect(['user/index']);
        }

        //跳转页面,并传值
        return $this->render('add',['user'=>$user]);
    }
    //删除
    public function actionDel($id){
        $user = User::findOne(['id'=>$id]);
        $user->status = 0;
        $user->save(false);
        return $this->redirect(['user/index']);
    }

    //登录
    public function actionLogin(){
        //1 认证(检查用户的账号和密码是否正确)
        $user = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $user->load($request->post());
            if($user->validate() && $user->login()){
                //登录成功
                \Yii::$app->session->setFlash('success','登录成功');
                $model = User::findOne(['username'=>$user->username]);
//                echo \Yii::$app->request->getUserIP();exit;
                $model->last_login_time = time();
//                $model->last_login_ip = $_SERVER["REMOTE_ADDR"];
                $model->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $model->save(false);
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['user'=>$user]);

    }
    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                //
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }


    //修改自己的密码
    public function actionChange(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);
        }
        //创建一个新的修改密码模型
        $model = new ChangeForm();
        //从session中找到该用户数据
        $user = User::findOne(['id'=>\Yii::$app->user->identity['id']]);
        //实例化Request组件
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
                if($model->validate() && $model->change()){
                    $user->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
                    $user->save(false);
                    \Yii::$app->session->setFlash('success','修改密码成功');
                    return $this->redirect(['user/index']);
                }
        }
        return $this->render('change',['model'=>$model]);
    }
}
