<?php

use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp;

if (!extension_loaded('phalcon')) {
    print_r('Please Install Phalcon framework');
    exit(1);
}
define('APP_PATH', realpath('..'));
include __DIR__ . '/../apps/config/loader.php';
$config = include __DIR__ . '/../apps/config/config.php';
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        __DIR__ . '/../apps/tasks'
    )
);
$loader->register();
$di = new CliDI();
$di->set('modelsManager', 'Phalcon\Mvc\Model\Manager', 1);
$di->set('modelsMetadata', 'Phalcon\Mvc\Model\MetaData\Memory', 1);
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = array();
$params = array();

foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}
$di->setShared('console', $console);

try {

    $console->handle($arguments);
    if (isset($config["printNewLine"]) && $config["printNewLine"])
        echo PHP_EOL;

} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}