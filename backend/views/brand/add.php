<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'name');
echo $form->field($brand,'intro')->textarea();
echo $form->field($brand,'imgFile')->fileInput();
echo $form->field($brand,'sort')->textInput(['type'=>'number']);
echo $form->field($brand,'status',['inline'=>1])->radioList(\backend\models\Brand::getStatusOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
