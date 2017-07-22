<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use flyok666\uploadifive\UploadAction;

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
         //实例化一个ArticleCategory模型
         $model = new ArticleCategory();
         //实例化request组件
         $request = new Request();
         //判断是否是post方式传值
         if($request->isPost){
             //加载模型数据
             $model->load($request->post());
             //判断是否通过验证
             if($model->validate()){
                 //保存数据到数据库
                 $model->save();
             }else{
                 //没通过验证打印错误
                 var_dump($model->getErrors());exit;
             }
             //跳转到index页面
             return $this->redirect(['article-category/index']);
         }
         //不是post方式提交
         return $this->render('add',['model'=>$model]);
     }
     public function actionEdit($id){
         //根据条件实例化一个数据模型
         $model =  ArticleCategory::findOne(['id'=>$id]);
         //实例化request组件
         $request = new Request();
         //判断是否是post方式传值
         if($request->isPost){
             //加载模型数据
             $model->load($request->post());
             //判断是否通过验证
             if($model->validate()){
                 //保存数据到数据库
                 $model->save();
             }else{
                 //没通过验证打印错误
                 var_dump($model->getErrors());exit;
             }
             //跳转到index页面
             return $this->redirect(['article-category/index']);
         }
         //不是post方式提交
         return $this->render('add',['model'=>$model]);
     }
     public function actionDel($id){
         //根据条件实例化一个数据模型
         $model =  ArticleCategory::findOne(['id'=>$id]);
         //根据要求逻辑删除   把status值修改为-1
         $model->status = -1;
         //保存数据到数据库
         $model->save();
         //跳转页面到index
         return $this->redirect(['article-category/index']);
     }

}