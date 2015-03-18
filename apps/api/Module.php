<?php
namespace Api;
class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(
            array(
                __DIR__ . '/controllers/'
            )
        );
        $loader->register();
    }

    public function registerServices($di)
    {
        $di->set('dispatcher', function () {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultController('monitor');
            $dispatcher->setDefaultAction('servicelist');
            //$dispatcher->setDefaultNamespace("Demo\\Controllers\\");
            return $dispatcher;
        });
    }
}
