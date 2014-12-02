<?php
/**
 * Description of Model
 * 模型类
 * @category Libary
 * @author zl
 */
class Model
{
    /**
     * 表主键名称
     * @var type 
     */
    protected $_pk = 'id';
    /**
     * 当前数据库操作对象
     * @var type 
     */
    protected $db = null;
    /**
     * 数据表前缀
     * @var type 
     */
    protected $_tablePref = '';
    /**
     * 数据库表名
     * @var type 
     */
    protected $_table_name = '';
    /**
     * 模型名称
     * @var type 
     */
    private $name = '';

    /**
     * 构造函数
     */
    public function __construct($tablePref = '')
    {
        //初始化模型
        $this->initialize();
        if(empty($this->name))
        {
            $this->name = $this->getModelName();
        }
        //设置表的前缀
        if(is_null($tablePref))
        {
            $this->_tablePref = '';
        }
        else if('' != $tablePref)
        {
            $this->_tablePref = $tablePref;
        }
        else
        {
            $this->_tablePref = $this->_tablePref ? $this->_tablePref : P('DB_TABLE_PREFIX');
        }

        //数据库初始化操作，获取数据库操作对象
        $this->dbManager(0);
    }
    
    /**
     * 回调方法，初始化模型
     */
    private function initialize(){}
    
    /**
     * 获取当前数据对象的名称
     * @return string
     */
    private function getModelName()
    {
        if(empty($this->name))
        {
            preg_match('/model\_/', get_class($this), $matches);
            $this->name = substr($matches[0], strlen($matches[0]));
        }
        
        return $this->name;
    }

    /**
     * 切换当前的数据库连接
     * @staticvar array $_db
     * @param int $linkNum 连接序号
     * @return \Model
     */
    private function dbManager($linkNum = '')
    {
        if(($linkNum === '') && $this->db)
        {
            return $this->db;
        }
        static $_db = array();
        if(!isset($_db[$linkNum]))
        {
            $_db[$linkNum] = Db::getInstance();
        }
        //切换数据库连接
        $this->db = $_db[$linkNum];
        
        return $this;
    }
    
    /**
     * 查询数据集合
     * @access public
     * @param string $sql 查询语句
     * @return boolean|null
     */
    protected function select()
    {
        $this->db->_setTableName($this->getTableName());
        $resultSet = $this->db->select();
        if($resultSet === false)
        {
            return false;
        }
        if(empty($resultSet))
        {
            return NULL;  //查询结果为空
        }
        
        return $resultSet;
    }
    
    /**
     * 添加数据
     * @param array $data 新增数据
     * @return int
     * @throws Exception
     */
    protected function save($data)
    {
        if(empty($data) || !is_array($data))
        {
            throw new Exception('parameter is error.');
        }
        
        $this->db->_setTableName($this->getTableName());
        $result = $this->db->insert($data);
        if($result === false)
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    /**
     * SQL查询条件
     * @access protected
     * @param mixes $param 查询条件
     * @return Model
     */
    protected function where($param)
    {
        $this->db->parseWhere($param);
        
        return $this;
    }
    
    /**
     * SQL查询的字段
     * @param mixed $param 字段信息
     * @return Model
     */
    protected function fileds($param)
    {
        $this->db->parseFiled($param);
        
        return $this;
    }
    
    /**
     * SQL语句limit条件
     * @param mixed $offset limit条件开始
     * @param int $length 长度
     * @return Model
     */
    protected function limit($offset, $length = null)
    {
        if(is_null($length) && strpos($offset, ','))
        {
            list($offset, $length) = explode(',', $offset);
        }
        $limit = intval($offset) . (empty($length) ? '' : ',' . intval($length));
        $this->db->parseLimit($limit);
        
        return $this;
    }
    
    /**
     * 获取数据库表名
     * @return string
     */
    protected function getTableName()
    {
        return $this->_table_name ? $this->_tablePref . $this->_table_name : '';
    }
}
