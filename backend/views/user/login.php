<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
echo $form->field($user,'password')->passwordInput();
echo $form->field($user,'remember')->checkbox();
//验证码
echo $form->field($user,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'user/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();