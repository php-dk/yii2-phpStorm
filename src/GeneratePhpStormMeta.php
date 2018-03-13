<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 13.03.18
 * Time: 17:29
 */

namespace phpdk\yii2PhpStorm;


class GeneratePhpStormMeta
{
    protected $config;

    /**
     * GeneratePhpStormMeta constructor.
     * @param $dir
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getModules()
    {
        $modules = [];
        foreach ($this->config as $name => $item) {
            if ($name == 'modules') {
                foreach ($item as $moduleName => $params) {
                    $modules[$moduleName] = $params['class'];
                }
            }
        }

        return $modules;
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
    
    public function create($dir)
    {
        $modules = $this->getModules();
        fwrite(fopen($dir . '.phpstorm.meta.php', 'w'), $this->getGeneratePhpStormMeta($modules));
    }

}
