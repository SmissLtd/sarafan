<?php
namespace app\assets;

class AuthAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/auth.css',
    ];
    public $js = [
        'js/auth.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
