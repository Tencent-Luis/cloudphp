<?php
/**
 * 运行时公共文件
 * @category Common
 * @author zl <john.doe@example.com>
 */
defined('ROOT_PATH') or die();

//自定义项目路径
$script_path = dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'));
defined('APP_PATH') or define('APP_PATH', $script_path . '/');

//路径设置
defined('CLOUD_PATH') or define('CLOUD_PATH', ROOT_PATH);
defined('CORE_PATH') or define('CORE_PATH', CLOUD_PATH . 'Libary/');   //核心类目录
defined('EXTEND_PATH') or define('EXTEND_PATH', CLOUD_PATH . 'Extend/');   //扩展类目录
defined('APP_PATH_COMMON') or define('APP_PATH_COMMON', APP_PATH . 'common/');
defined('APP_PATH_CONTROLLER') or define('APP_PATH_CONTROLLER', APP_PATH . 'controller/');
defined('APP_PATH_MODEL') or define('APP_PATH_MODEL', APP_PATH . 'model/');
defined('APP_PATH_VIEW') or define('APP_PATH_VIEW', APP_PATH . 'template/');

/**
 * 创建自定义项目文件的文件路径
 */
function build_App_Dir()
{
    //检测自定义项目文件夹app是否存在，无则创建
    if(!is_dir(APP_PATH))
    {
        mkdir(APP_PATH, 0755, true);
    }
    
    if(is_writeable(APP_PATH))
    {
        $dirs = array(
            APP_PATH_COMMON,
            APP_PATH_CONTROLLER,
            APP_PATH_MODEL,
            APP_PATH_VIEW,
        );
        foreach ($dirs as $dir)
        {
            if(!is_dir($dir))
            {
                mkdir($dir, 0755, true);
            }
        }
    }
    else
    {
        header('Content-Type:text/html; charset=utf-8');
        die('自定义项目目录不可写，目录无法自动生成！<br>请手动生成自定义项目目录。');
    }
}

/**
 * 加栽运行时所需要的文件
 */
function load_Runtime_File()
{
    //加栽公共处理文件
    require CLOUD_PATH . 'Common/common.php';
    //读取核心文件列表
    $list = array(
        CORE_PATH . 'Core/Ibase.php',
        CORE_PATH . 'Core/cloud.php',
        CORE_PATH . 'Core/router.php',
        CORE_PATH . 'Core/controller.php',
        CORE_PATH . 'Core/model.php',
        CORE_PATH . 'Core/view.php',
        CORE_PATH . 'lib_exception.php',
        
        EXTEND_PATH . 'redis.class.php',
    );
    //加栽核心文件列表
    foreach ($list as $file)
    {
        if(is_file($file))
        {
            requireCache($file);
        }
    }
    
    build_App_Dir();  //创建自定义项目目录
}

//加载运行时所需文件
load_Runtime_File();

//执行入口
Cloud::start();
