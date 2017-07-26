<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
echo $form->field($user,'password')->passwordInput(['value'=>'']);
//echo $form->field($user,'repassword')->passwordInput();
if(!$user->isNewRecord){
    echo $form->field($user,'status',['inline'=>1])->radioList(\backend\models\User::$status_options);
}

echo $form->field($user,'email');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();