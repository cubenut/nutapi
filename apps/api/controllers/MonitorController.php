<?php

class monitorController extends \Phalcon\Mvc\Controller
{

    public function runAction()
    {
        //$service = $this->request->get('service');
        //$param = $this->request->get('param');
        //$serviceClass = \Engine\ApiFactory::generateService($service, $this->di);
        //$action = $service[2];
        //return isset($param) ? call_user_func(array(get_class($serviceClass), $action), $param) : call_user_func(array(get_class($serviceClass), $action));
        //$obj = new MyClass();
        //return call_user_func(array($serviceClass, $action));
    }

    /**
     * 返回列表
     */
    public function servicelistAction()
    {
        $list = include APP_PATH . '/apps/config/services.php';
        return $list;
    }

    /**
     * 获取单条数据
     */
    public function servicegetAction()
    {
        $service = $this->request->get('service');
        $list = include APP_PATH . '/apps/config/services.php';
        if (isset($list[$service])) {
            return $list[$service];
        } else {
            return array();
        }
    }
}
