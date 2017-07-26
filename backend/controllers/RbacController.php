<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class RbacController extends \yii\web\Controller
{
    //权限列表
    public function actionIndexPermission()
    {
        //实例化权限组件
        $authManager = \Yii::$app->authManager;
        //获取所有权限
        $models = $authManager->getPermissions();

        //跳转页面，并传输数据
        return $this->render('index-permission',['models'=>$models]);

    }
    //添加权限
    public function actionAddPermission(){
        //实例化权限表单模型
        $model = new PermissionForm(['scenario'=>PermissionForm::SCENARIO_ADD]);
        //实例化request组件
        $request = new Request();
        //判断是否是post提交
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //实例化权限组件
                $authManager = \Yii::$app->authManager;
                //创建权限
                $permission = $authManager->createPermission($model->name);
                $permission->description = $model->description;
                //保存权限
                $authManager->add($permission);
                \Yii::$app->session->setFlash('success','权限添加成功');
                //跳转页面
                return $this->redirect(['rbac/index-permission']);
            }
        }

        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name){
        //检查权限是否存在
        $authManager = \Yii::$app->authManager;
        //根据权限名获取该权限
        $permission = $authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //权限存在
        $model = new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //将表单提交的值赋值给权限模型
                $permission->name = $model->name;
                $permission->description=$model->description;
                //更新权限
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','权限修改成功');
                //调用视图
                return $this->redirect(['rbac/index-permission']);
            }
        }else{
            //回显
            $model->name = $permission->name;
            $model->description = $permission->description;

        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name){
        //实例化权限组件
        //检查权限是否存在
        $authManager = \Yii::$app->authManager;
        //获取该权限
        $permission = $authManager->getPermission($name);
        //删除该权限
        $authManager->remove($permission);

        \Yii::$app->session->setFlash('success','权限删除成功');

        //跳转页面
        return $this->redirect(['rbac/index-permission']);
    }
    //添加角色
    public function actionAddRole(){

    }
    //修改角色
    public function actionEditRole(){

    }
    //角色列表
    public function actionIndexRole(){

    }
    //删除角色
    public function actionDelRole(){

    }

}
