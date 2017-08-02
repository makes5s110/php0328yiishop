<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [

    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //把语言设置成中文
    'language'=>'zh-CN',
    //修改默认路由
    'defaultRoute'=>'member/index',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'loginUrl'=>['member/ajax-login'],
//            'identityClass' => 'common\models\User',
            'identityClass' => 'frontend\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'sms'=>[
            'class'=>\frontend\components\AliyunSms::className(),
            'accessKeyId'=>'LTAIxeJqEGQRCmj9',
            'accessKeySecret'=>'OnIYegZuhb9zZsNTb9yqPUeowe9HuH',
            'signName'=>'源代码京西商城',
            'templateCode'=>'SMS_80085052'
        ]

    ],
    'params' => $params,
];
