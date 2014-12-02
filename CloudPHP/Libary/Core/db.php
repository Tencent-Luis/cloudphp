<?php
/**
 * Description of Db
 * 数据库中间层实现类
 * @category Cloud
 * @package Core
 * @author luis
 */
class Db implements Ibase
{
    /**
     * 数据库类型
     * @var string 
     */
    protected $dbType = null;
    /**
     * 数据库连接ID
     * @var array 
     */
    protected $linkID = array();
    /**
     * 当前连接ID
     * @var int 
     */
    protected $_linkID = null;
    /**
     * 是否成功连接数据库
     * @var boolean 
     */
    protected $connected = false;
    /**
     * 当前正在执行的sql命令
     * @var string 
     */
    protected $sqlStr = '';
    /**
     * 数据库连接参数配置
     * @var string 
     */
    protected $config = '';
    /**
     * SQL语句where条件
     * @var string 
     */
    protected $_where = '';
    /**
     * SQL语句filed信息
     * @var string 
     */
    protected $_fileds = '';
    /**
     * SQL语句limit条件
     * @var string 
     */
    protected $_limit = '';
    /**
     * 设置表名(包含表前缀)
     * @var string 
     */
    protected $_tableName = '';

    protected $selectSQL = 'SELECT %DISTINCT% %FIELD% FROM %TABLE% %JOIN% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT% %UNION% %COMMENT%';

    /**
     * 获取数据库实例
     * @return Object
     * @throws Exception
     */
    public static function getInstance()
    {
        if(method_exists(__CLASS__, 'factory'))
        {
            return get_object_instance(__CLASS__, 'factory');
        }
        else
        {
            throw new Exception('类:'.__CLASS__.'访问的方法factory不存在!');
        }
    }
    
    /**
     * 加载数据库类，并生成对象
     * @access protected
     * @return Object
     * @throws Exception
     */
    public function factory()
    {
        //读取数据库配置
        $db_config = $this->parseConfig();
        if(empty($db_config['dbtype']))
        {
            throw new Exception("未配置数据库类型{$db_config['dbtype']}。");
        }

        $this->dbType = ucwords(strtolower($db_config['dbtype']));
        $class = $this->dbType;
        //检查相应的数据库类
        if(class_exists($class))
        {
            $db = new $class($db_config['default']);
        }
        else
        {
            throw new Exception("类{$class}不存在。");
        }
        
        return $db;
    }
    
    /**
     * 分析数据库的配置信息
     * @access private
     * @return mixed
     */
    private function parseConfig()
    {
        if(1 == P('IS_DB_DSN'))   //DSN方式连接数据库
        {
            
        }
        else
        {
            $db_config = array(
                'dbtype' => P('DB_TYPE'),
                'default' => P('DEFAULT'),
                'read' => P('READ'),
                'write' => P('WRITE'),
            );
        }
        
        return $db_config;
    }
    
    /**
     * 初始化数据库连接
     * @access protected
     */
    protected function initConnect($master = true)
    {
        //判断数据库是读写分离式，还是单一
        if(P('DB_DEPLOY_TYPE') == 1)
        {
            $this->_linkID = $this->master_slave_connection($master);
        }
        else
        {
            //默认是单数据库连接
            if(!$this->connected)
            {
                //调用子类方法connection()，父类中要生成子类对象才可以调用
                $this->_linkID = $this->connection();
            }
        }
    }
    
    /**
     * 分布式数据库连接(主从)
     * @param bool $master 是否采用主从
     * @return resource
     */
    protected function master_slave_connection($master = false)
    {
        $_config = $this->parseConfig();
        if(P('DB_RW_SEPARATE'))
        {
            if($master)
            {
                $way = self::DB_WRITE_TYPE;
            }
            else
            {
                $way = self::DB_READ_TYPE;
            }
        }
        else
        {
            $way = self::DB_CONNECT_DEFAULT;
        }
        
        $db_config = array(
            'host' => $_config[$way]['host'],
            'port' => $_config[$way]['port'],
            'dbname' => $_config[$way]['dbname'],
            'username' => $_config[$way]['username'],
            'password' => $_config[$way]['password'],
            'charset' => $_config[$way]['charset'],
        );
        return $this->connection($db_config, $way);
    }

    /**
     * select查询操作
     * @return array
     */
    public function select()
    {
        $this->_where = empty($this->_where) ? '' : 'WHERE ' . $this->_where;
        $this->_fileds = empty($this->_fileds) ? '*' : $this->_fileds;
        
        $sql = "SELECT {$this->_fileds} FROM {$this->_tableName} {$this->_where} {$this->_limit}";
        $result = $this->query($sql);
        
        return $result;
    }
    
    /**
     * 设置表名
     * @param string $tablename 表名称
     */
    public function _setTableName($tablename)
    {
        $this->_tableName = $tablename;
    }
    
    /**
     * insert操作
     * @param array $data 被新增的数据信息
     * @return int
     */
    public function insert($data)
    {
        $fileds = $values = array();
        foreach ($data as $key => $val)
        {
            $fileds[] = $key;
            $values[] = $val;
        }
        
        $sql = "INSERT INTO {$this->_tableName}"." (`".implode('`,`', $fileds)."`) VALUES ('".implode("','", $values)."')";
        return $this->execute($sql);
    }
    
    /**
     * 构建SQL语句字段信息
     * @param mixed $mixed 字段条件信息
     * @return string
     */
    public function parseFiled($mixed)
    {
        if(empty($mixed) || count($mixed) == 0)
        {
            $this->_fileds = ' * ';
        }
        
        if(is_array($mixed))
        {
            $this->_fileds = '`' . implode('`,`', $mixed) . '`';
        }
        else
        {
            $this->_fileds = $mixed;
        }
    }

    /**
     * 构建SQL语句查询条件
     * @param mixed $condition 查询条件
     */
    public function parseWhere($condition)
    {
        if(empty($condition) || count($condition) == 0)
        {
            $this->_where = '';
        }
        
        $where = '';
        if(is_array($condition))
        {
            foreach ($condition as $key => $value)
            {
                $where .= "`{$key}`='{$value}' AND "; 
            }
            $this->_where = rtrim($where, 'AND ');
        }
        else
        {
            $this->_where = $condition;
        }
    }
    
    /**
     * SQL语句limit条件
     * @param string $limit 起始位置
     */
    public function parseLimit($limit)
    {
        $this->_limit = empty($limit) ? '' : 'LIMIT ' . $limit;
    }
    
    /**
     * 关闭数据库
     */
    public function close(){}
}
