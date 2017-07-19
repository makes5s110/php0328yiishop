<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller{
     public function actionIndex(){
         //按照条件查询所有数据
         $query = ArticleCategory::find()->where(['!=','status','-1'])->orderBy('sort');
         //获取总条数
         $total = $query->count();
         //设置每页显示条数
         $PageSize = 3;
         //实例化分页工具类
         $page = new Pagination([
             'totalCount'=>$total,
             'defaultPageSize'=>$PageSize
         ]);
         $models = $query->limit($page->limit)->offset($page->offset)->all();
         return $this->render('index',['models'=>$models,'page'=>$page]);
     }
     public function actionAdd(){
         $model = new ArticleCategory();
         $request = new Request();
         if($request->isPost){
             $model->load($request->post());
             if($model->validate()){
                 $model->save();
             }else{
                 return $model->getErrors();exit;
             }
             return $this->redirect(['article-category/index']);
         }
         return $this->render('add',['model'=>$model]);
     }
     public function actionEdit($id){
         $model =  ArticleCategory::findOne(['id'=>$id]);
         $request = new Request();
         if($request->isPost){
             $model->load($request->post());
             if($model->validate()){
                 $model->save();
             }else{
                 return $model->getErrors();exit;
             }
             return $this->redirect(['article-category/index']);
         }
         return $this->render('add',['model'=>$model]);
     }
     public function actionDel($id){
         $model =  ArticleCategory::findOne(['id'=>$id]);
         $model->status = -1;
         $model->save();
         return $this->redirect(['article-category/index']);
     }
}