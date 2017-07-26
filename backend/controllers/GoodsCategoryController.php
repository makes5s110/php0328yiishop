<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
//use yii\data\Pagination;
use yii\web\HttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();
//        //总条数
//        $total = $query->count();
//        //默认显示条数
//        $PageSize = 3;
//        //实例化一个分页工具类
//        $page = new Pagination(
//            ['totalCount'=>$total,
//            'defaultPageSize'=>$PageSize]
//        );
//        $models = $query->limit($page->limit)->offset($page->offset)->all();
////        var_dump($models);exit;
        return $this->render('index',['models'=>$models/**,'page'=>$page*/]);
    }
    public function actionAdd()
    {
        $model = new GoodsCategory(['parent_id' => 0]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $name = $model->name;
            $parent_id = $model->parent_id;
            $a = GoodsCategory::find()->andWhere(['name' => $name, 'parent_id' => $parent_id])->all();
            if ($a) {
                throw new HttpException(404, '不能再同分类下添加相同的名称');
            } else {
                if ($model->parent_id) {
                    //非一级分类
                    $category = GoodsCategory::findOne(['id' => $model->parent_id]);
                    if ($category) {
                        $model->appendTo($category);
                    } else {
                        throw new HttpException(404, '上级分类不存在');
                    }
                } else {
                    //一级分类
                    $model->makeRoot();
                }

                //判断成功过后，不能直接保存
                //判断是否是添加一级分类
                \Yii::$app->session->setFlash('success', '分类添加成功');
                return $this->redirect(['index']);

            }
        }
            //获取所以分类数据
            $categories = GoodsCategory::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
            return $this->render('add', ['model' => $model, 'categories' => $categories]);
    }
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //判断成功过后，不能直接保存
            //判断是否是添加一级分类
            $name = $model->name;
            $parent_id = $model->parent_id;
            $a = GoodsCategory::find()->andWhere(['name' => $name, 'parent_id' => $parent_id])->all();
            if ($a) {
                throw new HttpException(404, '不能再同分类下修改为相同的名称');
            } else {
                if ($model->parent_id) {
                    //非一级分类

                    $category = GoodsCategory::findOne(['id' => $model->parent_id]);
                    if ($category) {
                        $model->appendTo($category);
                    } else {
                        throw new HttpException(404, '上级分类不存在');
                    }

                } else {
                    //bug fix:修复根节点修改为根节点的bug
                    if ($model->oldAttributes['parent_id'] == 0) {
                        $model->save();
                    } else {
                        //一级分类
                        $model->makeRoot();
                    }
                }

            }
            \Yii::$app->session->setFlash('success', '分类修改成功');
            return $this->redirect(['goods-category/index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionDel($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        //如果分类不存在 抛出异常
        if($model == null){
            throw new HttpException(404,'该分类不存在！');
        }
//        var_dump($model->id);exit;
        //判断该分类下是否有子分类
        if(($model->rgt-$model->lft)==1){
            //没有子分类，删除该分类  不能用delete()
            $model->deletewithChildren();
            \Yii::$app->session->setFlash('danger','删除成功！');
        }else{
            \Yii::$app->session->setFlash('warning','该分类下还有子分类，不能删除！');
        }
        return $this->redirect(['goods-category/index']);
    }
//    //测试嵌套结合使用
//    public function actionTest(){
//        //创建一个根节点
////        $category = new GoodsCategory();
////        $category->name = "家用电器";
////        $category->makeRoot();
//        //创建一个字节点
//        $category2 = new GoodsCategory();
//        $category2->name = "电视";
//        $category = GoodsCategory::findOne(['id'=>1]);
//        $category2->parent_id=$category->id;
//        $category2->prependTo($category);
//        echo '创建成功';
//    }

}
