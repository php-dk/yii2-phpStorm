<?php

namespace interoit\yii2Tips;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('upgrade fias JKH')
            ->setHelp("This command upgrade fias");

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
            '. implode(", \n" , $code).'
        ]));     
}';
        return $template;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $array = array_merge_recursive(
            include __DIR__ . '/config/web.php',
            include __DIR__ . '/config/main.php',
            include __DIR__ . '/config/main-local.php',
            include __DIR__ . '/config/web-local.php'
        );

        @mkdir('./vendor/phpStormTips');
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