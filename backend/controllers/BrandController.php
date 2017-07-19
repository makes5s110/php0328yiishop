<?php
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{
    public function actionIndex(){
        //查找status不为-1的所有数据
        $query = Brand::find()->where(['!=','status','-1'])->orderBy('sort');
        //总条数
        $total = $query->count();
        //$brands = Brand::find()->where(['!=','status','-1'])->all();
        //每页显示的条数
        $PageSize = 3;
        //分页工具类
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$PageSize
        ]);
        //按照条件查询数据
        $brands = $query->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }
    public function actionAdd(){
        $brand = new Brand();
        $request = new Request();
        if($request->isPost){
            //实例化一个图片对象
            $brand->load($request->post());
            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');


            if($brand->validate()){
                if($brand->imgFile){
                    //指定目录 \Yii::getAlias 解析别名@webroot
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //指定文件路径
                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
                    //移动文件 不删除临时文件
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    $brand->logo = $filename;
                }

            }else{
                    var_dump($brand->getErrors());exit;
            }
            $brand->save(false);
            return $this->redirect(['brand/index']);
        }
        return $this->render('add',['brand'=>$brand]);
    }
    public function actionEdit($id){
        $brand = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            //实例化一个图片对象
            $brand->load($request->post());
            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');


            if($brand->validate()){
                if($brand->imgFile){
                    //指定目录 \Yii::getAlias 解析别名@webroot
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //指定文件路径
                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
                    //移动文件 不删除临时文件
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    $brand->logo = $filename;
                }

            }else{
                var_dump($brand->getErrors());exit;
            }
            $brand->save(false);
            return $this->redirect(['brand/index']);
        }
        return $this->render('add',['brand'=>$brand]);
    }
    public function actionDel($id){
        $brand = Brand::findOne(['id'=>$id]);
        $brand->status=-1;
        $brand->save();
        return $this->redirect(['brand/index']);
    }
}