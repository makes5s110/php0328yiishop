<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;

class CartController extends Controller{
    public $enableCsrfValidation=false;
    public $layout=false;
    public function actionAddToCart($amount, $goods_id)
    {
        //判断该用户是否登录
        if (\Yii::$app->user->isGuest) {
            //将数据保存到cookie中
            $cookie = \Yii::$app->request->cookies;
            //判断cookie中是否存在值如果不存在保存值到cookie中
            $goods = $cookie->get('goods');
            if ($goods == null) {
                $cates = [$goods_id => $amount];
            } else {
                //取出cookie对应的值
                $cates = unserialize($goods->value);
                //判断id是否有值有就叠加
                if (isset($cates[$goods_id])) {
                    $cates[$goods_id] += $amount;
                    //没有就添加
                } else {
                    $cates[$goods_id] = $amount;
                }

            }
            //保存到cookie中
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'goods',
                'value' => serialize($cates),
                'expire' => 2 * 24 * 3600 + time(),
            ]);
            $cookies->add($cookie);
        } else {
            $model = new Cart();
            $member_id = \Yii::$app->user->identity->getId();
            $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$goods_id])
                ->one();
            if (!$models) {
                $model->amount = $amount;
                $model->goods_id = $goods_id;
                $model->member_id = $member_id;
                $model->save(false);
            } else {
                $models->amount += $amount;
                $models->save();
            }
        }
        return $this->redirect(['cart/cart']);

    }

    public function actionCart()
    {
        //判断用户是否登录
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $model = $cookies->get('goods');
            if ($model == null) {
                $models = [];
            } else {
                $models = unserialize($model->value);
            }
            $rows = Goods::find()->where(['in', 'id', array_keys($models)])->all();
            return $this->render('cart', ['models' => $rows, 'cart' => $models]);
        } else {

            $member_id = \Yii::$app->user->identity->getId();
            $models = Cart::find()->where(['member_id' => $member_id])->all();
            $goods_id = [];
            $cart = [];
            foreach ($models as $model) {
                //将得到得goods_id放入数组中
                $goods_id[] = $model->goods_id;
                //模拟一个数组显示页面用
                $cart[$model->goods_id] = $model->amount;
            }
            //查询出所有商品
            $models = Goods::find()->where(['in', 'id', $goods_id])->all();
            return $this->render('cart', ['models' => $models, 'cart' => $cart]);
        }


    }

    //修改购物车数据
    public function actionAjaxCart($goods_id,$amount)
    {
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('goods');
            if ($cart == null) {
                $carts = [$goods_id => $amount];
            } else {
                $carts = unserialize($cart->value);
                if (isset($carts[$goods_id])) {
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                } else {
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'goods',
                'value' => serialize($carts),
                'expire' => 2 * 24 * 3600 + time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else{
            $member_id = \Yii::$app->user->identity->getId();

            $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$goods_id])
                ->one();
            $models->amount = $amount;
            $models->save();


        }

    }
    /* //修改购物车数据
     public function actionAjaxCart()
     {
         $goods_id = \Yii::$app->request->post('goods_id');
         $amount = \Yii::$app->request->post('amount');
         //数据验证
         if(\Yii::$app->user->isGuest){
             $cookies = \Yii::$app->request->cookies;
             //获取cookie中的购物车数据
             $cart = $cookies->get('cart');
             if($cart==null){
                 $carts = [$goods_id=>$amount];
             }else{
                 $carts = unserialize($cart->value);
                 if(isset($carts[$goods_id])){
                     //购物车中已经有该商品，更新数量
                     $carts[$goods_id] = $amount;
                 }else{
                     //购物车中没有该商品
                     $carts[$goods_id] = $amount;
                 }
             }
             //将商品id和商品数量写入cookie
             $cookies = \Yii::$app->response->cookies;
             $cookie = new Cookie([
                 'name'=>'cart',
                 'value'=>serialize($carts),
                 'expire'=>7*24*3600+time()
             ]);
             $cookies->add($cookie);
             return 'success';
         }
     }*/

    public function actionDelete($id){
        if(!\Yii::$app->user->isGuest){
            $member_id=\Yii::$app->user->identity->getId();
            $models = Cart::find()
                ->andWhere(['member_id'=>$member_id])
                ->andWhere(['goods_id'=>$id])
                ->one();
            $models->delete();
            return $this->redirect(['cart/cart']);
        }
        else{
            $cookies=\Yii::$app->request->cookies;
            $carts=unserialize($cookies->get('goods'));
            unset($carts[$id]);
            $cookies=\Yii::$app->response->cookies;
            //实例化cookie
            $cookie=new Cookie([
                'name'=>'goods',//cookie名
                'value'=>serialize($carts) ,//cookie值
                'expire'=>2*24*3600+time(),//设置过期时间
            ]);
            $cookies->add($cookie);//将数据保存到cookie
            return $this->redirect(['cart/cart']);

        }
    }
}