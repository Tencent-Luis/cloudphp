<?php
/**
 * 配置文件
 * 配置名称大小写任意，系统会统一转成小写
 * @category CloudPHP
 * @package Conf
 * @author luis <john.doe@example.com>
 */
defined('CLOUD_PATH') or die();

return array(
    'DB_DEPLOY_TYPE' => 1,    //数据库部署方式:0-集中式(单一服务器)，1-分布式(主从服务器)
    'DB_RW_SEPARATE' => TRUE,   //数据库读写是否分离，主从式有效
    'DB_TYPE' => 'mysql',   //数据库类型
    'DB_TABLE_PREFIX' => 'cloud_',   //数据库表前缀
    'IS_DB_DSN' => FALSE,   //是否使用DSN方式连接数据库
    
    /*关系型数据库设置*/
    'DEFAULT' => array(
        'host' => isset($_SERVER['SERVER_DB_HOST_R']) ? $_SERVER['SERVER_DB_HOST_R'] : '192.168.137.28',  //服务器地址
        'dbname' => isset($_SERVER['SERVER_DB_NAME_R']) ? $_SERVER['SERVER_DB_NAME_R'] : 'bch_weixin_leju_com',  //数据库名
        'username' => isset($_SERVER['SERVER_DB_USER_R']) ? $_SERVER['SERVER_DB_USER_R'] : 'root',  //用户名
        'password' => isset($_SERVER['SERVER_DB_PWD_R']) ? $_SERVER['SERVER_DB_PWD_R'] : '123456',  //密码
        'port' => isset($_SERVER['SERVER_DB_PORT_R']) ? $_SERVER['SERVER_DB_PORT_R'] : '3306',  //端口号
        'charset' => 'utf-8',  //数据库编码默认采用utf8
        'dsn' => '',
    ),
    
    'READ' => array(
        'host' => filter_input(INPUT_SERVER, 'SERVER_DB_HOST_R'),
        'port' => filter_input(INPUT_SERVER, 'SERVER_DB_PORT_R'),
        'username' => filter_input(INPUT_SERVER, 'SERVER_DB_USER_R'),
        'password' => filter_input(INPUT_SERVER, 'SERVER_DB_PWD_R'),
        'dbname' => filter_input(INPUT_SERVER, 'SERVER_DB_NAME_R'),
        'charset' => 'utf-8',
    ),
    'WRITE' => array(
        'host' => filter_input(INPUT_SERVER, 'SERVER_DB_HOST'),
        'port' => filter_input(INPUT_SERVER, 'SERVER_DB_PORT'),
        'username' => filter_input(INPUT_SERVER, 'SERVER_DB_USER'),
        'password' => filter_input(INPUT_SERVER, 'SERVER_DB_PWD'),
        'dbname' => filter_input(INPUT_SERVER, 'SERVER_DB_NAME'),
        'charset' => 'utf-8',
    ),
);