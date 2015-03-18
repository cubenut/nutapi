<?php
namespace Demo\Models;

class User extends \Phalcon\Mvc\Model
{

    public $id;
	

    //设置表名
    public function getSource()
    {
        return "user";
    }

    public function getId()
    {
        return $this->id;
    }
	
}