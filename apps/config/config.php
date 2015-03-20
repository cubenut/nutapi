<?php

return new \Phalcon\Config(array(

    'site' => array(
        'name' => 'nutapi',
        'url' => 'http://nut.gongchang.cn',
        'project' => 'nut',
        'docs' => 'https://open.gongchang.cn',
    ),

    'database' => array(
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'nutapi',
        'charset' => 'utf8'
    ),

    'clients' => array(
        1 => array(
            'name' => 'client1',
            'ip' => '127.0.0.1',
            'secret' => 'JD67sx',
        ),
        2 => array(
            'name' => 'client2',
            'ip' => '127.0.0.1',
            'secret' => 'xc21D3',
        )
    )
));

//$di->set("config",function() use ($config){
//    return $config;
//});
//$config = $this->getDI()->get("config");
