<?php
namespace Engine;
/**
 *      ApiFactory 创建控制器类 工厂方法
 *      将创建与使用分离，简化调用，负责控制器复杂的创建过程
 *      根据请求(?service=XXX.XXX.XXX)生成对应的接口服务，并进行初始化
 *      $api = ApiFactory::generateService();
 */
class ApiFactory
{
    //返回api服务对象
    public static function generateService(&$service, $di)
    {

        $serviceArr = explode('.', $service);
        if (count($serviceArr) < 2) {
            throw new \Exception("service ({$service}) illegal1", 600);
        }

        list($module, $apiClassName, $action) = $serviceArr;
        require APP_PATH . '/apps/' . $module . '/Module.php';
        //call_user_func(array("\\" . ucfirst($module) . "\\Module", "registerAutoloaders"));
        $ModuleClass = ucfirst($module) . "\\Module";
        $moduleClass = new $ModuleClass();
        $moduleClass->registerAutoloaders();
        $moduleClass->registerServices($di);

        $apiClassName = ucfirst($module) . "\\Services\\" . ucfirst($apiClassName);
        $action = lcfirst($action);
        if (!class_exists($apiClassName)) {
            throw new \Exception("no such service as {$service}1", 404);
        }

        $api = new $apiClassName();
        if (!method_exists($api, $action) || !is_callable(array($api, $action))) {
            throw new \Exception("no such method as {$service}2", 405);
        }
        $service = $serviceArr;
        return $api;
    }
}
