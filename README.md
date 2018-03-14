# Install

composer require php-dk/yii2-phpstorm:@dev --dev

# Configuration

console-local.php
```
return [
    'modules' => [
        'tips' => [
            'class' => \phpdk\yii2PhpStorm\Module::class,
            'config' => \yii\helpers\ArrayHelper::merge(
                require 'main.php',
                require 'main-local.php',
                ... 
            )
        ]
    ],
];
    

```

# Run

./yii tips/gen/application - generate Application class 
./yii tips/gen/meta - generate .phpstorm.meta.php file in basePath
./yii tips/gen - all

# Use
 
После обновления будут доступны подсказки для имён модулей, и компоненты,
.phpstorm.meta.php - начинает работать только после перезапуска IDE.


```
  Yii::$app->component->...
  Yii::$app->getModule('...')->...
```
PhpStorm "multiple definitions exist for class"
To hide this message:

Find a duplicate class file (not created by this generator), for example: vendor/yiisoft/yii/YiiBase.php
Mark it as a plain text in file context menu.


