<?php
namespace Demo;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(
            array(
                "Demo\\Controllers" => __DIR__ . '/controllers/',
                "Demo\\Services" => __DIR__ . '/services/',
                "Demo\\Models" => __DIR__ . '/models/'
            )
        );
        $loader->register();
    }

    public function registerServices($di = null)
    {

        $config = include APP_PATH . "/apps/config/config.php";

        $di->set('dispatcher', function () {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultController('user');
            $dispatcher->setDefaultAction('index');
            $dispatcher->setDefaultNamespace("Demo\\Controllers\\");
            return $dispatcher;
        });

        $di->set('db', function () use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });
    }
}
