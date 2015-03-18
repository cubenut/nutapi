<?php
/**
 * nutapi入口文件
 */
$t1 = microtime(true);

define('APP_PATH', realpath('..'));
define('APP_STAGE', 'development');
if (APP_STAGE == 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
/////
if (!extension_loaded('phalcon')) {
    print_r('Install Phalcon framework!');
    exit(1);
}

//composer包管理
//require APP_PATH . "/apps/composer/vendor/autoload.php";

if (isset($_GET['xhprof'])) {
    xhprof_enable();
}

try {

    require_once APP_PATH . '/apps/config/loader.php';
    $transport = isset($_GET['transport']) ? $_GET['transport'] : 'http';
    $service = isset($_GET['_service']) ? $_GET['_service'] : 'demo.user.info';

    //$api = new \Engine\NutApi();
    $api = \Engine\NutApi::instance();
    if ($transport == 'http') {
        $res = $api->runNormal();
    } elseif ($transport == 'yar') {
        $res = $api->runMicro($service);
        exit(1);
    }

    $t2 = microtime(true);
    //封装返回data客户端
    $response = $api->di->get("response");
    //$response = new \Engine\Response();
    $runtime = round($t2 - $t1, 3) * 1000;
    $response->outData($res, 200, $runtime);


} catch (\Exception $e) {
    //异常处理
    $response = new \Engine\Response();
    $response->outData($e->getMessage(), 600, 0, $e->getTraceAsString());
}

if (isset($_GET['xhprof'])) {
    $data = xhprof_disable();
    $objXhprofRun = new XHProfRuns_Default('xhprof');
    $objXhprofRun = new XHProfRuns_Default('/tmp/xhprof/');
    $run_id = $objXhprofRun->save_run($data, "xhprof");
    $url = "http://115.159.0.153/xhprof_html/?run={$run_id}&source=xhprof";
    echo $url;
}
