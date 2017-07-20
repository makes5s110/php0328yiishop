
<p style="text-align: center;font-size: 2em"><?=$article->name?></p>
<div><?=date('Y-m-d H:i:s',$article->create_time)?></div>
<hr/>
<div style="text-align: left;font-size: 14px"><?=$model->content?></div>
<?=\yii\bootstrap\Html::a('返回',['article/index'],['class'=>'btn btn-info'])?>