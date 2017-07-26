<?php echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])
/* @var $this yii\web\View */
?>
<?php
$form = \yii\bootstrap\ActiveForm::begin(['layout'=>'inline','method'=>'get','action'=>['goods/index']]);
echo $form->field($goods_search,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($goods_search,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($goods_search,'minPrice')->textInput(['placeholder'=>'最小金额'])->label(false);
echo $form->field($goods_search,'maxPrice')->textInput(['placeholder'=>'最大金额'])->label(false);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-default glyphicon glyphicon-search']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
<?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->sn?></td>
        <td><?=$model->name?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['style'=>'max-height:50px'])?></td>
        <td><?=\yii\bootstrap\Html::a('相册',['goods/index'],['class'=>'btn btn-success glyphicon glyphicon-picture'])?>
            <?=\yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-warning glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
            <?=\yii\bootstrap\Html::a('预览',['goods/index'],['class'=>'btn btn-info glyphicon glyphicon-eye-open'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页'])?>