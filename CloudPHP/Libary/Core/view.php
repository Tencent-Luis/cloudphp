<?php
/**
 * Description of View
 * 视图类
 * @category Libary
 * @author zl
 */
class View
{
    /**
     * 模版输出变量
     * @access protected
     * @var _var 
     */
    protected $_var = array();

    /**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name, $value = '')
    {
        if(!isset($this->_var[$name]))
        {
            $this->_var[$name] = $value;
        }
    }
    
    public function show($templateFile = '')
    {
        $templateFile = $this->parseTemplate($templateFile);
        
        //页面缓存
        ob_start();
        ob_implicit_flush(0);
        //从数组中将变量赋值到当前的符号表中,extract()
        extract($this->_var, EXTR_OVERWRITE); 
        include_once $templateFile;
        ob_end_flush();
        flush();
    }
    
    /**
     * 自动定位模版文件
     * @param string $templateFile 模版文件
     * @return string
     */
    protected function parseTemplate($templateFile = '')
    {
        defined('APP_PATH_VIEW') ? APP_PATH_VIEW : define('APP_PATH_VIEW', APP_PATH . 'template/');
        $view_path = APP_PATH_VIEW . $templateFile . '.php';
        if(!is_file($view_path))
        {
            throw new Exception('404,模版文件'.$templateFile.'未找到！');
        }
        
        if(!strpos($templateFile, '/'))
        {
            return $view_path;
        }
        else
        {
            $parts = explode('/', $templateFile);
            return APP_PATH_VIEW . $parts[1] . '.php';
        }
    }
}
