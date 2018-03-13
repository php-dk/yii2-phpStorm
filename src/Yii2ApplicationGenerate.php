<?php

namespace phpdk\yii2PhpStorm;

class Yii2ApplicationGenerate
{
    private $config = [];

    /**
     * Generate constructor.
     * @param string $saveDir
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function getClassTemplate()
    {
        return /** @lang PHP */
            '<?php
        class Yii {
            /** @var App $app  */
            public static $app;
        }
        
        class App {
             //{code}
        }
        ';
    }
    
    protected function generatePropertyDocComment()
    {
        $property = '';
        foreach ($this->config as $name => $item) {
            if ($name == 'components') {
                foreach ($item as $componentName => $data) {
                    if (is_callable($data)) {
                        if ($result = $data()) {
                            $class = get_class($result);
                        }

                    } else {
                        $class = $data['class'] ?? false;
                    }

                    if (!isset($class) || !$class) continue;
                    $property .= " /** @var $class \$$componentName  */ \n public \$$componentName; \n";
                }
            }
        }
        
        return $property;
    }


    public function create(string $dir)
    {
        $directory = $dir . '/phpStormTips';
        if (!file_exists($directory)) {
            mkdir($dir . '/phpStormTips');
        }
        
        $code = $this->getClassTemplate();
        $property = $this->generatePropertyDocComment();
        
        $code = str_replace('//{code}', $property, $code);
        fwrite(fopen($dir . '/phpStormTips/mock.php', 'w'), $code);
    }
}
