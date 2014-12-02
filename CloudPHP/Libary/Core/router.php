<?php
/**
 * Description of Router
 * 路由解析类
 * 完成URL解析、路由和调度
 * @category Libary
 * @author zl
 */
class Router
{
    /**
     * 默认控制器
     * @var string 
     */
    private static $_default_controller = 'default';
    /**
     * 默认动作
     * @var string 
     */
    private static $_default_action = 'index';
    /**
     * 控制器前缀
     * @var string 
     */
    private static $_prex_controller = 'Controller';
    /**
     * 动作前缀
     * @var string 
     */
    private static $_prex_action = 'Action';

    /**
     * 从URL映射到控制器
     * @static
     */
    public static function run()
    {
        $request_url = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $parts = explode('/', $request_url);
        
        //获取当前访问的controller
        if(empty($parts[1]))
        {
            $controller = self::$_default_controller . self::$_prex_controller;
        }
        else
        {
            $controller = strtolower($parts[1]) . self::$_prex_controller;
        }
        //获取当前执行的action
        if(empty($parts[2]))
        {
            $action = self::$_default_action . self::$_prex_action;
        }
        else
        {
            $action = strtolower($parts[2]) . self::$_prex_action;
        }
        
        if(is_dir(APP_PATH_CONTROLLER))
        {
            //当前请求的controller路径
            $request_file = APP_PATH_CONTROLLER . $controller . '.php';
            if(is_file($request_file))
            {
                require_once $request_file;
                $object = C($controller);
                
                //通过反射执行相应的类和方法
                $class = new ReflectionClass($controller);
                if($class->hasMethod($action))
                {
                    $method = $class->getMethod($action);
                    if($method->isPublic())
                    {
                        $method->invoke($object);
                    }
                }
                else
                {
                    throw new Exception('访问的'.$action.'不存在。');
                }
            }
            else
            {
                throw new Exception('访问的'.$controller.'.php不存在。');
            }
        }
        else
        {
            throw new Exception('访问的'.APP_PATH_CONTROLLER.'不存在。');
        }
    }
}
