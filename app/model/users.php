<?php
/**
 * Description of users
 * 用户实体类
 * @author leju
 */
class model_users extends Model
{
    protected $_pk = 'id';
    protected $_table_name = 'users';   //重写父类声明的变量，上同
    
    public function getAllRecords()
    {
        $result = $this->select();
        
        return $result;
    }
    
    public function addRecord($data)
    {
        if(empty($data) || count($data) == 0)
        {
            return false;
        }
        
        $row_num = $this->save($data);
        return $row_num;
    }
}

