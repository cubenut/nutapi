<?php
namespace Demo\Controllers;

class UserController extends \Phalcon\Mvc\Controller
{

    public function infoAction()
    {
        $user = new \Demo\Services\User();
        return $user->info();
    }

    /**
     * 单条信息
     */
    public function getAction()
    {
        $id = intval($this->request->get('id'));
        if($id>0){
            $user = new \Demo\Services\User();
            return $user->get($id);
        }else
            return "用户id不能为空";
    }

    /**
     * 列表
     */
    public function itemsAction()
    {
        $offset = intval($this->request->get('limit'));
        $limit = intval($this->request->get('limit'));
        $order = trim($this->request->get('limit'));
        (!$limit) && $limit = 20;
        $arr = array();
        ($offset > 0) && $arr['offset'] = $offset;
        ($limit > 0) && $arr['limit'] = $limit;
        (!empty($order)) && $arr['order'] = $order;
        $user = new \Demo\Services\User();
        return $user->items($arr);
    }

    /**
     * 添加
     */
    public function addAction()
    {
        $username = trim($this->request->get('username'));
        $password = trim($this->request->get('password'));
        if (!empty($username) && !empty($password)) {
            $arr = array(
                'username' => $username,
                'password' => $password
            );
            $user = new \Demo\Services\User();
            return $user->add($arr);
        } else {
            return '用户名和密码不能为空';
        }
    }

    /**
     * 修改
     */
    public function updateAction()
    {
        $id = intval($this->request->get('id'));
        $username = trim($this->request->get('username'));
        $password = trim($this->request->get('password'));
        if ($id > 0) {
            $arr = array('id' => $id);
            (!empty($username)) && $arr['username'] = $username;
            (!empty($password)) && $arr['password'] = $password;
            $user = new \Demo\Services\User();
            return $user->update($arr);
        } else {
            return '用户id不能为空';
        }
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        $id = intval($this->request->get('id'));
        if ($id > 0) {
            $user = new \Demo\Services\User();
            return $user->delete($id);
        } else {
            return '用户id不能为空';
        }
    }
}
