<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($menu,'name')->textInput(['placeholder'=>'菜单名称']);
echo $form->field($menu,'parent_id')->dropDownList(\backend\models\Menu::getMenuOptions());
echo $form->field($menu,'url')->dropDownList(\backend\models\Menu::getUrlOptions(),['prompt'=>'请选择路由']);
echo $form->field($menu,'sort')->textInput(['placeholder'=>'排序']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();