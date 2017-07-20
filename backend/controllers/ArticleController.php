<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller{
    //首页
    public function actionIndex($keywords=''){
        //按照条件查找所有文章
        $query = Article::find()->where(['and','status>-1',"name like '%{$keywords}%'"])->orderBy('sort');
        //获取总条数
        $total = $query->count();
        //设置每页显示条数
        $PageSize = 3;
        //实例化一个分页工具类
        $page = new Pagination([
            //设置默认选项
            'totalCount'=>$total,
            'defaultPageSize'=>$PageSize
        ]);
        //获取每页所显示的文章
        $articles = $query->limit($page->limit)->offset($page->offset)->all();
        //调用视图，并传值 展示index页面
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }

    //添加方法
    public function actionAdd(){
        //实例化一个文章对象
        $article = new Article();
        //实例化一个文章详情对象
        $model = new ArticleDetail();
        //实例化request组件
        $request = new Request();
        if($request->isPost){
            //传值并展示页面
            $article->load($request->post());
            $model->load($request->post());
            //对两个模型的数据进行验证
            if($article->validate() && $model->validate()){
                //保存创建时间
                $article->create_time = time();
                $article->save();
                //把文章表的id赋值给详情表id，前提必须先保存文章表
                $model->article_id = $article->id;
                $model->save();
            }else{
                //验证失败，打印错误
                var_dump($article->getErrors() && $model->getErrors());exit;
            }
            //验证成功，跳转页面
            return $this->redirect(['article/index']);
        }
        //调用视图，并传值
        return $this->render('add',['article'=>$article,'model'=>$model]);
    }
    //修改方法
    public function actionEdit($id){
        //根据id找到一个文章对象
        $article = Article::findOne(['id'=>$id]);
        //根据id找到一个文章详情对象
        $model = ArticleDetail::findOne(['article_id'=>$id]);
        //实例化request组件
        $request = new Request();
        if($request->isPost){
            //传值并展示页面
            $article->load($request->post());
            $model->load($request->post());
            //对两个模型的数据进行验证
            if($article->validate() && $model->validate()){
                //保存数据
                $article->save();
                //把文章表的id赋值给详情表id，前提必须先保存文章表
//                $model->article_id = $article->id;
                $model->save();
            }else{
                //验证失败，打印错误
                var_dump($article->getErrors() && $model->getErrors());exit;
            }
            //验证成功，跳转页面
            return $this->redirect(['article/index']);
        }
        //调用视图，并传值
        return $this->render('add',['article'=>$article,'model'=>$model]);

    }
    //删除方法
    public function actionDel($id){
        //根据id找到一个文章对象
        $article = Article::findOne(['id'=>$id]);
        //修改文章状态为-1
        $article->status=-1;
        //保存数据
        $article->save();
        //跳转页面
        return $this->redirect(['article/index']);
    }
    //查看
    public function actionCheck($id){
        $article = Article::findOne(['id'=>$id]);
        $model = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('check',['article'=>$article,'model'=>$model]);
    }
    //百度UEditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}