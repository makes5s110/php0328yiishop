<?php
/* @var $this yii\web\View */
?>
<h1>菜单列表</h1>
<?php echo \yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info glyphicon glyphicon-plus']) ?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>名称</td>
        <td>路由</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($menus as $menu):?>
    <tr>
        <td><?=$menu->name?></td>
        <td><?=$menu->url?></td>
        <td><?=$menu->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-warning glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
        </td>
    </tr>

    <tr>
        <?php foreach ($menu->children as $child):?>
            <tr>
                <td>>>>><?=$child->name?></td>
                <td><?=$child->url?></td>
                <td><?=$child->sort?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$child->id],['class'=>'btn btn-warning glyphicon glyphicon-edit'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$child->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </tr>
    <?php endforeach;?>
</table>