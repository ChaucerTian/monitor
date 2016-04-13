<?php
require_once('smarty3/Smarty.class.php');

class smarty3 extends Smarty {
    public $yii = null;

    public function __construct(array $options=array()) {
        parent::__construct($options);
        spl_autoload_unregister('smartyAutoload');
        Yii::registerAutoloader('smartyAutoload');
        $this->setTemplateDir(DIR_ROOT . '/protected/templates/');
        $this->setCompileDir(DIR_ROOT . '/protected/runtime/smarty/compile/');
        //$this->setConfigDir(DIR_ROOT . '/protected/runtime/smarty/config/');
        $this->setCacheDir(DIR_ROOT . '/protected/runtime/smarty/cache/');
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

        $this->addPluginsDir(dirname(__FILE__) . '/smarty3/custom/');

        // 打开默认过滤
        $this->escape_html = true;
    }

    public function init() {
        $this->yii = Yii::app();
    }

    public function staticFileMap($file, $type, $module='', $debug=YII_DEBUG) {
        if ($module) {
            $module = '/' . $module;
            $moduleBase = DIR_ROOT . '/protected/modules/' . $module . '/';
        } else {
            $module = '';
            $moduleBase = DIR_ROOT . '/protected/';
        }

        if ($debug) {
            $ret = array(
                'realFile' => $moduleBase . $file,
                'webFile' => $module . '/' . $file,
                'type' => $type,
            );
        } else {
            if ($type === 'css' || $type === 'less') {
                $ret = array(
                    'realFile' => DIR_ROOT . $module . '/static/css/all.css',
                    'webFile' => $module . '/static/css/all.css',
                    'type' => 'css',
                );
            } elseif ($type === 'js') {
                $ret = array(
                    'realFile' => DIR_ROOT . $module . '/' . $file,
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
