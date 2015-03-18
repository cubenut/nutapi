<?php
/**
 * 自动加载类
 */

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    array(
        "Demo" => APP_PATH . '/apps/demo/',
        "Api" => APP_PATH . '/apps/api/',
        "Engine" => APP_PATH . '/apps/librarys/engine/'
    )
);
$loader->register();