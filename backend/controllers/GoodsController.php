<?php

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\NotFoundHttpException;
use backend\models\GoodsGallery;

class GoodsController extends \yii\web\Controller
{
    //首页
    public function actionIndex()
    {
        //实例化表单模型
        $goods_search = new GoodsSearchForm();
        $query= Goods::find();
        //调用模型中的方法（获取数据）
        $goods_search->search($query);
        //总条数
        $total=$query->count();
        //每页显示条数
        $PageSize = 3;
        //实例化分页工具类
        $page = new Pagination([
            //设置默认参数
            'totalCount'=>$total,
            'defaultPageSize'=>$PageSize
        ]);
        //获取查询的结果集
        $models = $query->limit($page->limit)->offset($page->offset)->all();

        return $this->render('index',['models'=>$models,'page'=>$page,'goods_search'=>$goods_search]);

    }
    //添加
    public function actionAdd(){
        //实例化goods模型对象
        $goods = new Goods();
        //实例化goodsintro模型对象
        $goodsintro = new GoodsIntro();
//        //实例化goodscategory模型对象
//        $models= new GoodsCategory(['parent_id'=>0]);

        //实例化request组件
        $request = new Request();
        if($request->isPost){
            $date = date('Ymd');
//            var_dump(date('Y-m-d H:i:s',time()));exit;
            $result = GoodsDayCount::findOne(['day'=>$date]);
//            var_dump((date('Ymd').'00000')+1);exit;
            if($result==null){
                $daycount = new GoodsDayCount();
                $daycount->day = $date;
//                var_dump($daycount->day);exit;
                $daycount->count = 1;
                $daycount->save();
                $goods->sn = date('Ymd').sprintf('%06d',$daycount->count);
            }else{
//                    var_dump(($result->count)+1);exit;
//                    $result->day = $date;
                    $result->count=($result->count)+1;
                    $result->save();
                    $goods->sn = date('Ymd').sprintf('%06d',$result->count);

            }//获取模型中值
            $goods->load($request->post())&&$goodsintro->load($request->post());
            if($goods->validate() && $goodsintro->validate()){
                $goods->create_time = time();
                $goods->status=1;
//                $goods->sn = date('Ymd').sprintf('%06d',($result->count)+1);
                $goods->save();
                //                var_dump($goods->sn);exit;
                $goodsintro->goods_id = $goods->id;
                $goodsintro->save();
                \Yii::$app->session->setFlash('success','添加成功');
            }

        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['goods'=>$goods,'goodsintro'=>$goodsintro,'categories'=>$categories]);

    }
    //修改
    public function actionEdit($id){
        $goods = Goods::findOne(['id'=>$id]);
        $goodsintro = GoodsIntro::findOne(['goods_id'=>$id]);
        if($goods->load(\Yii::$app->request->post()) && $goodsintro->load(\Yii::$app->request->post())) {

            if ($goods->validate() && $goodsintro->validate()) {
                $goods->save();$goodsintro->save();
                \Yii::$app->session->setFlash('success','商品修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['goods'=>$goods,'goodsintro'=>$goodsintro,'categories'=>$categories]);
    }
    //删除
    public function actionDel($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 0;
        $model->save();
        return $this->redirect(['goods/index']);
    }


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
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖

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
                },//文件的保存方式
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
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
//图片保存为本地相对路径
//                    $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "ar/wwwdocs/upload/image/yyyymmddtimerand.jpg"

                    //将图片上传到七牛云
//                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
//                    $qiniu->uploadFile(
//                        $action->getSavePath(), $action->getWebUrl()
//                    );
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $action->output['fileUrl'] = $url;

                    //商品相册保存到七牛云
//                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
//                    $qiniu->uploadFile(
//                        $action->getSavePath(), $action->getWebUrl()
//                    );
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $goods_id=\yii::$app->request->post('goods_id');
//                    if($goods_id){
//                        $model=new GoodsGallery();
//                        $model->goods_id=$goods_id;
//                        $model->path=$url;
//                        $model->save();
//                        $action->output['fileUrl'] = $model->path;
//                        $action->output['id'] = $model->id;
//                    }else{
//                        $action->output['fileUrl']  = $url;//输出文件的相对路径
//                    }
////

                }
            ],
        ];
    }

    /*
     * 商品相册
     */
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }

    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}
