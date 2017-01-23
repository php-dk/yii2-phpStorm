<?php

namespace interoit\yii2Tips;

class Generate
{
    protected $saveDir = 'vendor';
    private $config  = [];

    /**
     * Generate constructor.
     * @param string $saveDir
     */
    public function __construct($saveDir,array $config)
    {
        $this->saveDir = $saveDir;
        $this->config = $config;
    }

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
        @mkdir($this->saveDir . '/phpStormTips');
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
        foreach ($this->config as $name => $item) {
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
        fwrite(fopen($this->saveDir . '/phpStormTips/mock.php', 'w'), $code);

        fwrite(fopen('.phpstorm.meta.php', 'w'), $this->getGeneratePhpStormMeta($modules));
    }
}