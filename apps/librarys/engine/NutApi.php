<?php
/**
 * api入口类
 */

namespace Engine;

class NutApi
{

    public $config = null;
    public $di = null;
    public $loader = null;
    public $filter = null;
    private static $app;


    final public static function instance()
    {
        if (self::$app === null) {
            $class = __CLASS__;
            self::$app = new $class();
        }
		//统一错误处理
        ErrorHandler::set();
        self::$app->di = new \Phalcon\Di();
        if (APP_STAGE != 'development') {
            self::$app->filter = new Filter();
            self::$app->filter->check();
        }
        return self::$app;
    }


    public function runNormal()
    {
        define("NUAPI_RUN_ENV", "Web");

        $this->di->set('router', function () {
            $router = new \Phalcon\Mvc\Router();
            $router->setDefaultModule('api');
            $router->add('/:module/:controller/:action/:params', array(
                'module' => 1,
                'controller' => 2,
                'action' => 3
            ));
            return $router;
        });

        $this->di->set('dispatcher', 'Phalcon\Mvc\Dispatcher', 1);//分发器
        $this->di->set('modelsManager', 'Phalcon\Mvc\Model\Manager', 1);
        $this->di->set('modelsMetadata', 'Phalcon\Mvc\Model\MetaData\Memory', 1);
        //$this->di->set('response', 'Phalcon\Http\Response', 1);
        $this->di->set('response', 'Engine\Response', 1);
        $this->di->set('request', 'Phalcon\Http\Request', 1);

        $router = $this->di->getShared('router');
        $router->handle();

        $ModuleClass = ucfirst($router->getModuleName()) . "\\Module";
        if (class_exists($ModuleClass)) {
            $moduleClass = new $ModuleClass();
            $moduleClass->registerAutoloaders();
            $moduleClass->registerServices($this->di);
        }

        $dispatcher = $this->di->getShared('dispatcher');
        $dispatcher->setModuleName($router->getModuleName());
        $dispatcher->setControllerName($router->getControllerName());
        $dispatcher->setActionName($router->getActionName());
        $dispatcher->setParams($router->getParams());
        $dispatcher->dispatch();

        return $dispatcher->getReturnedValue();
    }

    public function runMicro($service = '')
    {
        define("NUAPI_RUN_ENV", "Micro");
        $this->di->set('modelsManager', 'Phalcon\Mvc\Model\Manager', 1);
        $this->di->set('modelsMetadata', 'Phalcon\Mvc\Model\MetaData\Memory', 1);
        $api = ApiFactory::generateService($service, $this->di);
        $ser = new \Yar_Server($api);
        $ser->handle();
    }
}
