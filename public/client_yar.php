<?php
$t1 = microtime(true);
$url = "http://nutapi.gongchang.cn/?_service=demo.user.info&transport=yar";

$client = new Yar_Client($url);

function callback($retval, $callinfo)
{
    if ($callinfo == NULL) {

        //做本地的逻辑
        return TRUE;
    }
    print_r($retval);
    //RPC请求返回, 返回值在$retval
}

function error_callback($type, $error, $callinfo)
{
    var_dump($type);
    var_dump($error);
    var_dump($callinfo);
}

//列表
Yar_Concurrent_Client::call($url, "items", array(), "callback");

//添加
//Yar_Concurrent_Client::call($url, "add", array(array('username'=>'gcd_nut', 'password'=>'gcd_nut')), "callback");

//修改
//Yar_Concurrent_Client::call($url, "update", array(array('id'=>3, 'username'=>'update_username')), "callback");


Yar_Concurrent_Client::loop("callback", "error_callback");

//try {
//    $result = $client->getLast();
//    print_r($result);
//} catch (Yar_Server_Exception $e) {
//    var_dump($e->getType());
//    var_dump($e->getMessage());
//}

$t2 = microtime(true);
echo 'runtime:' . round($t2 - $t1, 3) * 1000 . 'ms';
exit();
