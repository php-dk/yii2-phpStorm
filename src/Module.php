<?php

namespace phpdk\yii2PhpStorm;


class Module extends \yii\base\Module
{
    public $files = [
        'main.php',
        'main-local.php',
        'web.php',
        'web-local.php',
    ];

    public function init()
    {
        $this->controllerNamespace = __NAMESPACE__;
    }
}
