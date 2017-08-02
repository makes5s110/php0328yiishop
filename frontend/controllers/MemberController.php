<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{
    //取消使用模板
    public $layout = false;
    //防止跨站请求攻击
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $models = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['models'=>$models]);
    }
    public function actionAjaxLogin(){
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->login()){
                $model = Member::findOne(['username'=>$model->username]);
                $model->last_login_time = time();
                $model->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $model->save(false);
                //保存数据，提示保存成功
                return Json::encode(['status'=>true,'msg'=>'登录成功']);
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
//    public function actionRegister(){
//        $model = new Member();
//        $request = new Request();
//        $model->scenario = Member::SCENARIO_REGISTER;
//        if($request->isPost){
//            $model->load($request->post());
//            if($model->validate()){
//                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
//                $model->status = 1;
//                $model->auth_key = \Yii::$app->security->generateRandomString();
//                $model->created_at = time();
//                $model->save(false);
//            }
//        }

//        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//
//            \Yii::$app->session->setFlash('success','注册成功')
//            return $this->redirect(['member/login']);
//        }
//        return $this->render('register',['model'=>$model]);
//    }
//AJAX表单验证
    public function actionAjaxRegister()
    {
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $code = \Yii::$app->session->get('code_'.$model->tel);
                if($code && $model->smsCode){
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                    $model->status = 1;
                    $model->auth_key = \Yii::$app->security->generateRandomString();
                    $model->created_at = time();
                    $model->save(false);
                    //保存数据，提示保存成功
                    return Json::encode(['status'=>true,'msg'=>'注册成功']);
                }else{
                    $model->addError('smsCode','短信验证码错误');
                }

            }else{
                //验证失败，提示错误信息
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('register',['model'=>$model]);
    }
    //验证码
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
    //收货地址
    public function actionAddress(){
        $model = new Address();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->user_id = \Yii::$app->user->id;
                if($model->status){
                    $model->status = 1;
                }else{
                    $model->status = 0;
                }
                $model->save();
                return Json::encode(['status'=>true,'msg'=>'添加成功']);
            }else{
                //验证失败，提示错误信息
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('address',['address'=>$model]);
    }
    //回显
    public function actionAddressEdit($id){
        $model = Address::findOne(['id'=>$id]);
        $request = new Request();

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->status){
                    $model->status = 1;
                }else{
                    $model->status = 0;
                }
                $model->save();
                return Json::encode(['status'=>true,'msg'=>'添加成功']);
            }else{
                //验证失败，提示错误信息
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }else{

        }
    }
    //删除
    public function actionAddressDel($id){
        $model = Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['member/address']);
    }
//    public function actionLists($id){
//        $ress='';
//        $models = GoodsCategory::find()->where(['parent_id'=>$id])->all();
//        if($models){
//            foreach ($models as $model){
//                $result = GoodsCategory::find()->where(['parent_id'=>$model->id])->all();
//                if($result){
//                    foreach ($result as $re){
//                        $ress = Goods::find()->where(['goods_category_id'=>$re->id])->all();
//                        return $this->render('list',['ress'=>$ress]);
//                    }
//                }else{
//                    $ress = Goods::find()->where(['goods_category_id'=>$model->id])->all();
//                    return $this->render('list',['ress'=>$ress]);
//                }
//            }
//        }else{
//            $ress = Goods::find()->where(['goods_category_id'=>$id])->all();
//            return $this->render('list',['ress'=>$ress]);
//        }
//    }
    //列表页
    public function actionList($id){
        //找到该分类
        $category = GoodsCategory::findOne(['id'=>$id]);
        //depth为2表示为第三级
        if($category->depth==2){
            $ress = Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            //获取该分类下的所有叶子节点(leaves())
            $ids = $category->leaves()->asArray()->column();
            $ress = Goods::find()->where(['in','goods_category_id',$ids])->all();
        }
        return $this->render('list',['ress'=>$ress]);
    }
    //详情页
    public function actionGoods($id){
        $goods = Goods::findOne(['id'=>$id]);
        $gooods_info = GoodsIntro::findOne(['goods_id'=>$id]);
         return $this->render('goods',['goods'=>$goods,'goods_info'=>$gooods_info]);
    }
    //测试短信
    public function actionMessage()
    {
        $code = rand(10000,99999);
        $tel = '15228751706';
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['number'=>$code])->send();
        //将短信验证码保存redis(session,mysql)
        \Yii::$app->session->set('code_'.$tel,$code);
        //验证
        //$code2 = \Yii::$app->session->get('code_'.$tel);
    }
//    //注销
//    public function actionLogout(){
//        \Yii::$app->user->logout();
//        return $this->redirect(['member/ajax-login']);
//    }
}
