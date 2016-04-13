<?php
namespace yii\smarty;
use Yii;
use Smarty;

class smarty3 extends Smarty {
    // need to set template path alias first
    public $templatePath = '@template';
    /**
     * @var string the directory or path alias pointing to where Smarty cache will be stored.
     */
    public $cachePath = '@runtime/Smarty/cache';
    /**
     * @var string the directory or path alias pointing to where Smarty compiled templates will be stored.
     */
    public $compilePath = '@runtime/Smarty/compile';
    /**
     * @var array Add additional directories to Smarty's search path for plugins.
     */
    public $pluginDirs = [
        '@vendor/smarty/smarty/custom'
    ];
    /**
     * @var array Class imports similar to the use tag
     */
    public $imports = [];
    /**
     * @var array Widget declarations
     */
    public $widgets = ['functions' => [], 'blocks' => []];
    /**
     * @var array additional Smarty options
     * @see http://www.smarty.net/docs/en/api.variables.tpl
     */
    public $options = [];
    /**
     * @var string extension class name
     */
    public $extensionClass = '\yii\smarty\Extension';
    /**
     * @var Smarty The Smarty object used for rendering
     */
    protected $smarty;

    public function __construct(array $options=array()) {
        parent::__construct($options);
        $this->setCompileDir(Yii::getAlias($this->compilePath));
        $this->setCacheDir(Yii::getAlias($this->cachePath));
        //$this->setConfigDir(DIR_ROOT . '/protected/runtime/smarty/config/');
        $this->setTemplateDir(Yii::getAlias($this->templatePath));

        $this->caching = false;
        //$this->cache_lifetime = 6;

        if (YII_DEBUG) {
            //$this->force_compile = true;
        } else {
            $this->force_compile = false;
        }
        //
        //$this->debugging = true;

        $this->left_delimiter = '{%';
        $this->right_delimiter = '%}';

        $this->addPluginsDir($this->pluginDirs);

        // 打开默认过滤
        $this->escape_html = true;
    }



    public function init() {
    }

    public function staticFileMap($file, $type, $module='', $debug=YII_DEBUG) {
        $module = $module ? '/' . $module : '';
        if ($debug) {
            $ret = array(
                'realFile' => Yii::getAlias('@webroot')  . '/' . $file,
                'webFile' => $file,
                'type' => $type,
            );
        } else {
            if ($type === 'css' || $type === 'less') {
                $ret = array(
                    'realFile' => Yii::getAlias('@webroot') . '/assets/css/all.css',
                    'webFile' => Yii::getAlias('@webroot') . $module . '/assets/css/all.css',
                    'type' => 'css',
                );
            } elseif ($type === 'js') {
                $ret = array(
                    'realFile' => Yii::getAlias('@webroot') . $module . '/' . $file,
                    'webFile' => $module . '/' . $file,
                    'type' => $type,
                );
            }
        }
        $ret['md5'] = md5_file($ret['realFile']);
        Switch ($ret['type']) {
            case 'css':
                $ret['html'] = '<link rel="stylesheet" href="' . $ret['webFile'] . '?v=' . $ret['md5']. '">';
                break;
            case 'less':
                $ret['html'] = '<link rel="stylesheet/less" type="text/css" href="' . $ret['webFile'] . '?v=' . $ret['md5']. '">';
                break;
            case 'js':
                $ret['html'] = '<script src="' . $ret['webFile'] . '?v=' . $ret['md5'] . '"></script>';
                break;
            default:
                $ret['html'] = '';
        }
        return $ret;
    }
}
