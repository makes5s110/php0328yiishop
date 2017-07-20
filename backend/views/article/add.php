<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'article_category_id')->dropDownList(\backend\models\Article::getArticle());
echo $form->field($article,'sort')->textInput(['type'=>'number']);
//echo $form->field($model,'content')->textarea();
echo $form->field($model,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言 中文
        'lang' =>'zh-cn', //英文为 en
        //定制菜单
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]
    ]
]);
echo $form->field($article,'status',['inline'=>1])->radioList(\backend\models\Article::getArticleOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();