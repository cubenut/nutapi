<?php
/**
 *  服务过滤类
 */
namespace Engine;
class Filter
{

    protected $client_id;
    protected $token;
    protected $timestamp;

    public function check()
    {
        $this->client_id = isset($_GET['client_id']) ? $_GET['client_id'] : '1';
        $this->token = isset($_GET['token']) ? $_GET['token'] : '';
        $this->timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';
        $service = isset($_GET['service']) ? $_GET['service'] : 'demo.user.getLast';
        $this->validate($service, $this->client_id, $this->token, $this->timestamp);
    }

    public function validate($service = '', $client_id = 0, $token = '', $timestamp)
    {
        if ((time() - $timestamp) > 300) {
            throw new \Exception('service: ' . $service . ' time out!');
        }

        $services = include APP_PATH . "/apps/config/services.php";

        if (empty($services[$service])) {
            throw new \Exception('service: ' . $service . ' is not allow!');
        }

        $config = include APP_PATH . "/apps/config/config.php";
        $secret = $config['clients'][$client_id]['secret'];
        if (empty($secret)) {
            throw new \Exception('secret is not worng!');
        }

        if (!$services[$service]['isval']) {
            $arr = explode('.', $service . '.' . $timestamp);
            $apiToken = $this->sign($arr, $secret);

            if ($apiToken != $token) {
                throw new \Exception('service is expired!' . $apiToken);
            }
        }
    }

    public function sign($arr, $secret)
    {
        if (count($arr) == 0) {
            return "";
        }
        ksort($arr); //按照升序排序
        //$str = "";
        $str = http_build_query($arr);
        //$str = rtrim($str, "&");
        return substr(md5($str . $secret), 8);
    }
}