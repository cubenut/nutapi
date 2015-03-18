<?php
namespace Engine;
/**
 * 统一错误处理
 */
class ErrorHandler
{
    public static function handle($errno, $errstr, $errfile, $errline)
    {
        if (!error_reporting()) return;

        $logger = new \Phalcon\Logger\Adapter\File(APP_PATH . '/apps/logs/error.log');
        $logger->error($errstr);
        $logger->error($errfile . 'in line:' . $errline);

        throw new \Exception($errstr . " in $errfile:$errline" . $errno);
    }

    public static function set($error = E_ALL)
    {
        set_error_handler(array(__CLASS__, 'handle'), $error);
        //register_shutdown_function(array(__CLASS__, 'handle'));
    }
}
