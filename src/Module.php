<?php

namespace phpdk\yii2PhpStorm;

use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    public $files = [
        'main.php',
        'main-local.php',
        'web.php',
        'web-local.php',
    ];

    public function getConfigs()
    {
        $path = \Yii::$app->basePath;

        return ArrayHelper::merge(
            require($path . '/../config/main.php'),
            require($path . '/../config/main-local.php'),
            require($path . '/../config/web.php'),
            require($path . '/../config/web-local.php')
        );
    }

    public function init()
    {
        $this->controllerNamespace = __NAMESPACE__;
    }
}
