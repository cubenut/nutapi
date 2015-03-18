<?php

class mainTask extends \Phalcon\CLI\Task
{
    public function mainAction(array $params)
    {
        echo "================================================================";
        echo "
            ___  __       __              ____
           / _ \/ / ___ _/ _______  ___  / ____ _____
          / ___/ _ / _ `/ / __/ _ \/ _ \/ _// // / -_)
         /_/  /_//_\_,_/_/\__/\___/_//_/___/\_, /\__/
                                           /___/";
        print_r("\n================================================================\n");
        $service = $params[0];
        $api = \Engine\ApiFactory::generateService($service, $this->getDI());
        //$action = $params[0][2];
        //$param = $params[1];
        print_r(call_user_func(array($api, $service[2])));
//        $this->console->handle(array(
//            'task' => 'main',
//            'action' => 'test',
//            'params' => $params
//        ));
    }

    public function userAction(array $params)
    {
        $api = \Engine\ApiFactory::generateService($params[0], $this->getDI());
        print_r($api->get((int)$params[1]));
    }

}