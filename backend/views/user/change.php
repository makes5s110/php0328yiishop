<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_password')->passwordInput();
echo $form->field($model,'new_password')->passwordInput();
echo $form->field($model,'re_password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();