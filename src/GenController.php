<?php

namespace phpdk\yii2PhpStorm;

use yii\console\Controller;

class GenController extends Controller
{
    /** @var Module */
    public $module;
    
    public function actionIndex()
    {
        $this->actionMeta();
        $this->actionApplication();
    }

    public function actionMeta()
    {
        $meta = new GeneratePhpStormMeta($this->module->config);
        $meta->create(\Yii::$app->basePath);
    }

    public function actionApplication()
    {
        $generator = new Yii2ApplicationGenerate($this->module->config);
        $generator->create($this->module->vendorDir);
    }
}
