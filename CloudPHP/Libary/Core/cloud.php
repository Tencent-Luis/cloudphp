<?php
/**
 * Description of Cloud
 * CloudPHP 应用程序类 执行应用过程管理
 * @category Libary
 * @package Libary
 * @author zl
 */
class Cloud
{   
    /**
     * 运行应用实例的入口方法
     * @return void
     */
    public static function start()
    {
        //注册autoload方法
        spl_autoload_register(array('Cloud', 'autoload'));
        //项目初始化
        Cloud::init();
        //URL路由解析
        Router::run();
        
        return ;
    }
    
    /**
     * 应用程序初始化
     * @return void
     */
    public static function init()
    {
        //加载核心配置文件
        P(include CLOUD_PATH . 'Conf/config.php');
        
        //加载自定义项目公共文件
        if(is_file(APP_PATH_COMMON.'common.php'))
        {
            include APP_PATH_COMMON.'common.php';
        }
    }
    
    /**
     * 系统自动加载cloudphp类库
     * @param string $class 对象类名
     * @return type
     */
    public static function autoload($class)
    {
        $file = strtolower($class).'.php';   //将类名转为小写，以便加载对应的php文件
        $matches = array();
        preg_match('/model\_/', $class, $matches);
        if(preg_match('/model\_/', $class))  //匹配model类，并加载模型类
        {
            if(requireCache(APP_PATH_MODEL.substr($class, strlen($matches[0])).'.php'))
            {
                return ;
            }
        }
        if(requireCache(CORE_PATH.'Core/'.$file))  //加载核心类
        {
            return ;
        }
        if(requireCache(CORE_PATH.'Database/'.$file))  //加载数据库类
        {
            return ;
        }
    }
}
