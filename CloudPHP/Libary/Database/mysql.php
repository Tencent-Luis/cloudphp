<?php
/**
 * Description of Mysql
 * MySQL数据库驱动类
 * @category Libary
 * @package Database
 * @author luis
 */
defined('CLOUD_PATH') or die();

class Mysql extends Db
{
    /**
     * 构造函数，读取数据库的配置信息]
     * @access public
     * @param array $config 数据库配置数组
     * @throws Exception
     */
    public function __construct($config = '')
    {
        if(!extension_loaded('mysql'))
        {
            throw new Exception('Not support Mysql.');
        }
        if(!empty($config))
        {
            $this->config = $config;
        }
    }
    
    /**
     * 连接数据库的方法
     * @access public
     * @param array $config 数据库配置信息
     * @param int $linkNum 数据库连接标识
     * @return type
     * @throws Exception
     */
    public function connection($config = '', $linkNum = 0)
    {
        if(!isset($this->linkID[$linkNum]))
        {
            if(empty($config))
            {
                $config = $this->config;
            }
            //解决不带端口号的socket连接
            $host = $config['host'].($config['port'] ? ':'.$config['port'] : '');
            $this->linkID[$linkNum] = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname'], $config['port'], $host);
            if(!$this->linkID[$linkNum] || (!empty($config['dbname']) && !mysqli_select_db($this->linkID[$linkNum], $config['dbname'])))
            {
                throw new Exception('连接数据库 '.$config['dbname'].' 失败！');
            }
            //使用UTF8存取数据库
            mysqli_query($this->linkID[$linkNum], "SET NAMES '".$config['charset']."'");
            //获取mysql版本
            $dbversion = mysqli_get_server_info($this->linkID[$linkNum]);
            if($dbversion > '5.0.1')
            {
                mysqli_query($this->linkID[$linkNum], "SET sql_mode=''");
            }
            
            //标记连接数据库成功
            $this->connected = true;
            //注销数据库连接配置信息
            if(1 != P('DB_DEPLOY_TYPE'))
            {
                unset($this->config);
            }
        }
        
        return $this->linkID[$linkNum];
    }
    
    /**
     * 关闭数据库连接
     * @access public
     * @return void
     */
    public function close()
    {
        if($this->_linkID)
        {
            mysqli_close($this->_linkID);
        }
        $this->_linkID = NULL;
    }
    
    /**
     * sql语句错误信息，并显示当前执行的语句
     * @access public
     * @return void
     */
    public function error()
    {
        if($this->sqlStr != '')
        {
            echo mysqli_errno($this->_linkID).':'.mysqli_error($this->_linkID)."\n [SQL语句] : ".$this->sqlStr;
        }
    }
    
    /**
     * 查询操作，并返回数据集
     * @param string $sql 查询语句
     * @return array
     */
    public function query($sql)
    {
        $this->initConnect(false);
        if(!$this->_linkID)
        {
            return false;
        }
        $this->sqlStr = $sql;
        $resource = mysqli_query($this->_linkID, $sql);
        if($resource === false)
        {
            $this->error();
            return false;
        }
        else
        {
            $result = array();
            while($row = mysqli_fetch_assoc($resource))
            {
                $result[] = $row;
            }
            return $result;
        }
    }
    
    /**
     * 执行SQL查询
     * @param string $sql 被执行的SQL语句
     * @return int
     */
    public function execute($sql)
    {
        $this->initConnect(true);
        if(!$this->_linkID)
        {
            return false;
        }
        $this->sqlStr = $sql;
        $resource = mysqli_query($this->_linkID, $sql);
        if($resource === false)
        {
            $this->error();
            return false;
        }
        else
        {
            $numRows = mysqli_affected_rows($this->_linkID);
            return $numRows;
        }
    }
}
