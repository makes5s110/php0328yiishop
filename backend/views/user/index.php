
<h1>用户列表</h1>
<?php
echo \yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info glyphicon glyphicon-plus']);
if(!\Yii::$app->user->isGuest){
    echo \yii\bootstrap\Html::a('修改密码',['user/change'],['class'=>'btn btn-warning glyphicon glyphicon-edit']);
}
/* @var $this yii\web\View */
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user):?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
        <td><?= \yii\bootstrap\Html::a('修改',['user/edit','id'=>$user->id],['class'=>'btn btn-warning glyphicon glyphicon-edit'])?>
            <?= \yii\bootstrap\Html::a('删除',['user/del','id'=>$user->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?= \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页'])?>