<?= \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['height'=>50])?></td>
        <td><?=$brand->sort?></td>
        <td><?=\backend\models\Brand::getStatusOptions()[$brand->status]?></td>
        <td><?= \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-warning'])?>
            <?= \yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-danger'])?></td>
        <td></td>
    </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);
?>
