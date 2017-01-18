<?php

namespace interoit\yii2Tips;

class Generate
{
    public function getGeneratePhpStormMeta($modules)
    {

        $code = [];
        foreach ($modules as $name => $class) {
            $code[] = "\"$name\" => $class::class";
        }
        $template = '
<?php
namespace PHPSTORM_META {                                // we want to avoid the pollution
    override(  \yii\base\Module::getModule(0),
        map([
            ' . implode(", \n", $code) . '
        ]));     
}';

        return $template;
    }

    public function create()
    {
        $array = array_merge_recursive(
            include 'config/web.php',
            include 'config/main.php',
            include 'config/main-local.php',
            include 'config/web-local.php'
        );

        @mkdir('vendor/phpStormTips');
        $code /** @lang PHP */ = '<?php
        class Yii {
            /** @var App $app  */
            public static $app;
        }
        
        class App {
             //{code}
        }
        ';

        $modules = [];
        $property = '';
        foreach ($array as $name => $item) {
            if ($name == 'modules') {
                foreach ($item as $moduleName => $params) {
                    $modules[$moduleName] = $params['class'];
                }
            }
            if ($name == 'components') {
                foreach ($item as $componentName => $data) {
                    $class = $data['class'] ?? false;
                    if (!$class) continue;
                    $property .= " /** @var $class \$$componentName  */ \n public \$$componentName; \n";
                }
            }


        }

        $code = str_replace('//{code}', $property, $code);
        fwrite(fopen(__DIR__ . '/vendor/phpStormTips/mock.php', 'w'), $code);

        fwrite(fopen('.phpstorm.meta.php', 'w'), $this->getGeneratePhpStormMeta($modules));
    }
}