<?php

namespace phpdk\yii2PhpStorm;

use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    public $config = [];
    public $vendorDir;

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
        if (!$this->vendorDir) {
            $this->vendorDir = \Yii::$app->getBasePath() . '/vendor';
        }
        
        $this->controllerNamespace = '\phpdk\yii2PhpStorm';
    }

    public function getControllerPath()
    {
        return __DIR__;
    }
}

