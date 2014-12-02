<?php
/** 
 * 基础函数库
 * @category Common
 * @author zl <john.doe@example.com>
 */

/**
 * 优化的require_once
 * @staticvar array $_importFiles 保存文件值的数组
 * @param string $fileName 文件地址
 * @return boolean
 */
function requireCache($fileName)
{
    static $_importFiles = array();
    if(!isset($_importFiles[$fileName]))
    {
        if(is_file($fileName))
        {
            require $fileName;
            $_importFiles[$fileName] = true;
        }
        else
        {
            $_importFiles[$fileName] = false;
        }
    }
    
    return $_importFiles[$fileName];
}

/**
 * 实例化自定义controller类
 * @param string $class_name 类名
 * @return class_name|boolean
 */
function C($class_name)
{
    if(class_exists($class_name, false))
    {
        $controller = new $class_name();
        
        return $controller;
    }
    else
    {
        return false;
    }
}

/**
 * 获取和设置配置参数，支持批量定义
 * @staticvar array $_config 配置变量
 * @param array $name 配置变量
 * @param mixed $value 配置值
 * @return mixed
 */
function P($name = null, $value = null)
{
    static $_config = array();
    
    //设置字符串定义
    if(is_string($name))
    {
        $name = strtolower($name);
        if(is_null($value))
        {
            return isset($_config[$name]) ? $_config[$name] : null;
        }
        
        $_config[$name] = $value;
        return;
    }
    //批量设置(数组)
    if(is_array($name))
    {
        $_config = array_merge($_config, array_change_key_case($name));  //返回字符串名全为小写或大写的数组
        
        return;
    }
    
    return null;
}

/**
 * 获取对象实例
 * @staticvar array $_instance
 * @param string $name 类名
 * @param string $method 方法名，如果是空，返回实例化对象
 * @return \name
 * @throws Exception
 */
function get_object_instance($name, $method = '')
{
    static $_instance = array();
    $identify = $name.$method;
    if(!isset($_instance[$identify]))
    {
        if(class_exists($name))
        {
            $object = new $name();
            if(method_exists($object, $method))
            {
                $_instance[$identify] = $object->$method();
            }
            else
            {
                $_instance[$identify] = $object;
            }
        }
        else
        {
            throw new Exception('调用类'.$name.'不存在！');
        }
    }
    
    return $_instance[$identify];
}