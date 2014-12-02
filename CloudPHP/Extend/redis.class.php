<?php
/**
 * Description of redis
 * redis扩展类
 * @author luis
 * @time 2014-11-26
 */
class lib_redis
{
    private $_host = '192.168.137.28';
    private $port = 6379;
    private $_redis;
    private static $_instance;
    
    private function __construct()
    {
        $this->_redis = new Redis();
        $this->_redis->connect($this->_host, $this->port, 0);
    }
    
    private function __clone() {}
    
    /**
     * 获取redis实例的引用
     * @return object
     */
    public static function getInstance()
    {
        if(!(self::$_instance instanceof self))
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * 保存key-value数据
     * @param string $key
     * @param type $value 
     * @param type $expire_time 过期时间
     * @return bool
     * @throws Exception
     */
    public function set($key, $value = '', $expire_time = 0)
    {
        return $this->_redis->set($key, $value, $expire_time);
    }
    
    /**
     * 获取key-value数据
     * @param string $key
     * @return string
     * @throws Exception
     */
    public function get($key)
    {
        return $this->_redis->get($key);
    }
    
    /**
     * 将指定key的值加1
     * @param string $key 
     * @return int
     */
    public function incr($key)
    {
        return $this->_redis->incr($key);
    }
    
    /**
     * 将指定key的值减1
     * @param string $key
     * @return int
     */
    public function decr($key)
    {
        return $this->_redis->decr($key);
    }
}
