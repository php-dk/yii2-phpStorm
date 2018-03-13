<?php

namespace phpdk\yii2PhpStorm;


class Module extends \yii\base\Module
{
    public $config = [];
    public $vendorDir;
    
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

