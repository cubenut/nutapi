<?php
$t1 = microtime(true);
$url = array(
    "http://nutapi.gongchang.cn/demo/user/info",
    "http://nutapi.gongchang.cn/demo/user/info",
    "http://nutapi.gongchang.cn/demo/user/info",
    "http://nutapi.gongchang.cn/demo/user/info",
    "http://nutapi.gongchang.cn/demo/user/info",
);
$client = gets($url);

print('<pre>');
print_r(json_decode($client[0]));
print_r(json_decode($client[1]));
print_r(json_decode($client[2]));
print_r(json_decode($client[3]));
print_r(json_decode($client[4]));
print('</pre>');

$t2 = microtime(true);
echo 'runtime:' . round($t2 - $t1, 3) * 1000 . 'ms';
exit();

/**
 * CURL-get方式获取数据
 * @param string $url URL
 * @param array $data POST数据
 * @param string $proxy 是否代理
 * @param int $timeout 请求时间
 * @param array $header header信息
 */
function get($url, $proxy = null, $timeout = 10, $header = null)
{
    if (!$url) return false;
    $ssl = stripos($url, 'https://') === 0 ? true : false;
    $curl = curl_init();
    if (!is_null($proxy)) curl_setopt($curl, CURLOPT_PROXY, $proxy);
    curl_setopt($curl, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    }
    $cookie_file = APP_PATH . '/data/cookie.txt';
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); //连接结束后保存cookie信息的文件。
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);//包含cookie数据的文件名，cookie文件的格式可以是Netscape格式，或者只是纯HTTP头部信息存入文件。
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。
    curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //文件流形式
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //设置cURL允许执行的最长秒数。
    if (is_array($header))
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置请求的Header

    $content = curl_exec($curl);
    $curl_errno = curl_errno($curl);
    if ($curl_errno > 0) {
        $error = sprintf("curl error=%s, errno=%d.", curl_error($curl), $curl_errno);
        curl_close($curl);
        throw new Exception($error);
    }
    curl_close($curl);
    return $content;
}

/**
 * CURL-post方式获取数据
 * @param string $url URL
 * @param string $proxy 是否代理
 * @param int $timeout 请求时间
 * @param array $header header信息
 */
function post($url, $data, $proxy = null, $timeout = 10, $header = null)
{
    if (!$url) return false;
    if ($data) {
        $data = http_build_query($data);
    }
    $ssl = stripos($url, 'https://') === 0 ? true : false;
    $curl = curl_init();
    if (!is_null($proxy)) curl_setopt($curl, CURLOPT_PROXY, $proxy);
    curl_setopt($curl, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    }
    $cookie_file = APP_PATH . '/data/cookie.txt';
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); //连接结束后保存cookie信息的文件。
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);//包含cookie数据的文件名，cookie文件的格式可以是Netscape格式，或者只是纯HTTP头部信息存入文件。
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。
    curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_POST, true); //发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//Post提交的数据包
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //文件流形式
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //设置cURL允许执行的最长秒数。
    if (is_array($header))
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置请求的Header

    $content = curl_exec($curl);
    $curl_errno = curl_errno($curl);
    if ($curl_errno > 0) {
        $error = sprintf("curl error=%s, errno=%d.", curl_error($curl), $curl_errno);
        curl_close($curl);
        throw new Exception($error);
    }
    curl_close($curl);
    return $content;
}

/**
 * 从多个地址得到返回值CURL多线程实现
 * @param array $urls 所有要得到的URL地址数组
 * @param int $timeOut 总体超时间
 * @param array callback  对于得到的数据需要进行处理可以写到回调函数中比单独处理要高效
 */
function gets($urls, $timeOut = 0, $callback = array())
{
    if (!is_array($urls)) {
        exit('$urls 必须是数组');
    }
    $mcurl = curl_multi_init();
    $map = array();
    foreach ($urls as $key => $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_NOSIGNAL, true);
        curl_multi_add_handle($mcurl, $curl);
        $map[(string)$curl] = $key;
    }
    $strs = array();

    do {
        while ($code = curl_multi_exec($mcurl, $active) == CURLM_CALL_MULTI_PERFORM) ;

        if ($code != CURLM_OK) {
            break;
        }

        while ($done = curl_multi_info_read($mcurl)) {
            $httpCode = curl_getinfo($done['handle'], CURLINFO_HTTP_CODE);
            $results = '';
            if (200 == $httpCode) {
                $results = curl_multi_getcontent($done['handle']);
            }
            if (empty($callback)) {
                $strs[$map[(string)$done['handle']]] = $results;
            } else {
                if (is_array($callback)) {
                    //数组回调 两种方式 array($className, $staticmethod) array($obj,$method)
                    $strs[$map[(string)$done['handle']]] = call_user_func($callback, $results);
                } else {
                    //$caback函数方法
                    $strs[$map[(string)$done['handle']]] = call_user_func($callback, $results);
                }
            }
            curl_multi_remove_handle($mcurl, $done['handle']);
            curl_close($done['handle']);
        }

        if ($active > 0) {
            curl_multi_select($mcurl, 0.5);
        }
    } while ($active);
    curl_multi_close($mcurl);
    return $strs;
}
