<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MenuController extends \yii\web\Controller
{
    //菜单列表
    public function actionIndex()
    {
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['menus'=>$menus]);
    }

    //添加菜单
    public function actionAdd(){
        $menu = new Menu();
        $request = new Request();
        if($request->isPost){
            $menu->load($request->post());
            if($menu->validate()){
                $menu->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['menu'=>$menu]);
    }
    //修改菜单
    public function actionEdit($id){
        $menu = Menu::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $menu->load($request->post());
            if($menu->validate()){
                if(!empty($menu->children)){
                    $menu->addError('parent_id','只能为顶级菜单');
                }else{
                    $menu->save();
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['menu/index']);
                }
            }
        }
        return $this->render('add',['menu'=>$menu]);
    }
    //删除菜单
    public function actionDel($id){
        $menu = Menu::findOne(['id'=>$id]);
//        var_dump($menu->parent_id);exit;
        if(!empty($menu->children)){

            throw new NotFoundHttpException('不能删除，该分类下有子分类');
        }else{

            $menu->delete();
            return $this->redirect(['menu/index']);
        }


    }
//    public function behaviors()
//    {
//        return[
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//            ]
//        ];
//    }

}
