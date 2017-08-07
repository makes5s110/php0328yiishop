<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;

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
        if($brand->load(\Yii::$app->request->post()) && $brand->validate()){
//            var_dump($brand->logo);exit;
            $brand->save();
            \Yii::$app->session->setFlash('success','品牌添加成功');
            return $this->redirect(['brand/index']);
        }
        return $this->render('add',['brand'=>$brand]);
//        $brand = new Brand();
//        $request = new Request();
//        if($request->isPost){
//            //实例化一个图片对象
//            $brand->load($request->post());
//            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
//
//
//            if($brand->validate()){
////                if($brand->imgFile){
////                    //指定目录 \Yii::getAlias 解析别名@webroot
////                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
////                    if(!is_dir($d)){
////                        mkdir($d);
////                    }
////                    //指定文件路径
//////                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
//////                    //移动文件 不删除临时文件
//////                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
////                    $brand->logo = $filename;
////                }
//
//            }else{
//                    var_dump($brand->getErrors());exit;
//            }
//            $brand->save(false);
//            return $this->redirect(['brand/index']);
//        }
//        return $this->render('add',['brand'=>$brand]);
    }
    public function actionEdit($id){
        $brand = Brand::findOne(['id'=>$id]);
        if($brand==null){//如果品牌不存在，则显示404页面
            throw new NotFoundHttpException('品牌不存在');
        }
        if($brand->load(\Yii::$app->request->post()) && $brand->validate()){
            $brand->save();
            \Yii::$app->session->setFlash('success','品牌添加成功');
            return $this->redirect(['brand/index']);
        }
        return $this->render('add',['brand'=>$brand]);
//        $brand = Brand::findOne(['id'=>$id]);
//        $request = new Request();
//        if($request->isPost){
//            //实例化一个图片对象
//            $brand->load($request->post());
//            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
//
//
//            if($brand->validate()){
////                if($brand->imgFile){
////                    //指定目录 \Yii::getAlias 解析别名@webroot
////                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
////                    if(!is_dir($d)){
////                        mkdir($d);
////                    }
////                    //指定文件路径
////                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
////                    //移动文件 不删除临时文件
////                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
////                    $brand->logo = $filename;
////                }
//
//            }else{
//                var_dump($brand->getErrors());exit;
//            }
//            $brand->save(false);
//            return $this->redirect(['brand/index']);
//        }
//        return $this->render('add',['brand'=>$brand]);
    }
    public function actionDel($id){
        $brand = Brand::findOne(['id'=>$id]);
        $brand->status=-1;
        $brand->save();
        return $this->redirect(['brand/index']);
    }

    //uploadifive
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
//                    $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']  = $url;
                },
            ],
        ];
    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

//    //测试七牛云文件上传
//    public function actionQiniu()
//    {
//
//        $config = [
//            'accessKey'=>'w-Z7NO-zDQL9FN-Lm6aVO_3aPV0Lk2CS2hv8Gn5I',
//            'secretKey'=>'fZrsB7GYpDMzmwSQTd6tRg_i1BYzgc-DDbJREwgA',
//            'domain'=>'http://otbhsfl07.bkt.clouddn.com/',
//            'bucket'=>'yiishop',
//            'area'=>Qiniu::AREA_HUADONG
//        ];
//
//
//
//        $qiniu = new Qiniu($config);
//        $key = 'upload/2e/79/2e795418fcb72341d801d1fa70ca6fabc33444cb.png';
//
//        //将图片上传到七牛云
//        $qiniu->uploadFile(
//            \Yii::getAlias('@webroot').'/upload/2e/79/2e795418fcb72341d801d1fa70ca6fabc33444cb.png',
//            $key);
//        //获取该图片在七牛云的地址
//        $url = $qiniu->getLink($key);
//        var_dump($url);
//    }

}