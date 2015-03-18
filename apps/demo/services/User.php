<?php
namespace Demo\Services;
class User
{
    /**
     * user类信息
     * @param
     * @return string
     */
    public function info()
    {
        return 'Hello boy, this is user\'s api!';
    }

    /**
     * 获取单条信息
     * @param int id 用户id
     * @return array 单条用户数据
     */
    public function get($id)
    {
        $obj = \Demo\Models\User::findFirst("id={$id}");
        if (is_object($obj)) {
            return $obj->toArray();
        } else {
            return array();
        }
    }

    /**
     * 获取多条信息
     * @param array $arr 查询条件
     * @return array 多条用户数据
     */
    public function items($arr = array())
    {
        $resArr = \Demo\Models\User::find($arr);
        $return = array();
        if (!empty($resArr)) {
            foreach ($resArr as $obj) {
                if (is_object($obj))
                    $return[] = $obj->toArray();
            }
        }
        return $return;
    }

    /**
     * 添加单条信息
     * @param array $arr 添加数据数组
     * @return int 新增数据主键id；为0表示添加失败
     */
    public function add($arr = array())
    {
        if (empty($arr))
            return 0;
        $obj = new \Demo\Models\User();
        $obj->username = $arr['username'];
        $obj->password = $arr['password'];
        $obj->addtime = time();
        if ($obj->save() == false)
            return 0;
        else {
            return $obj->id;
        }
    }

    /**
     * 修改数据
     * @param array $arr 修改数据数组
     * @return int 1修改成功；0修改失败
     */
    public function update($arr = array())
    {
        $id = $arr['id'];
        if (empty($arr))
            return 0;
        $obj = \Demo\Models\User::findFirst("id={$id}");
        if (!is_object($obj))
            return 0;
        unset($arr['id']);
        foreach ($arr as $key => $value) {
            if ($obj->$key)
                $obj->$key = $value;
        }
        if ($obj->save() == false)
            return 0;
        else {
            return 1;
        }
    }

    /**
     * 删除单条数据
     * @param int $id 数据主键id
     * @return int 1删除成功；0删除失败
     */
    public function delete($id)
    {
        if ($id <= 0)
            return 0;
        $obj = \Demo\Models\User::findFirst($id);
        if (is_object($obj)) {
            if ($obj->delete() == false)
                return 0;
            else
                return 1;
        } else return 0;
    }
}