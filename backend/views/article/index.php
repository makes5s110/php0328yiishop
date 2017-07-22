<form action="" method="get">
    <div class="pull-right" style="padding-bottom: 5px">
        <input type="text" name="keywords" style="line-height: 28px" placeholder="请输入名称搜索关键字"/>
        <input type="submit" value="搜索" class="btn btn-info"/>
    </div>
</form>
<?= \yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info'])?>

<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=mb_substr($article->name,0,30)?></td>
        <td><?=mb_substr($article->intro,0,15)?></td>
        <td><?=$article->articleCategory->name?></td>
        <td><?=$article->sort?></td>
        <td><?= \backend\models\Article::getArticleOptions()[$article->status]?></td>
        <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
        <td><?=\yii\bootstrap\Html::a('查看',['article/check','id'=>$article->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id],['class'=>'btn btn-danger'])?>
        </td>
        <td></td>
    </tr>
    <?php endforeach;?>
</table>

<?= \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页'])?>
