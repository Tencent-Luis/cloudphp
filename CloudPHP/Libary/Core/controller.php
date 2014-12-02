<?php
/**
 * Description of Controller
 * 控制器类
 * @abstract class Controller
 * @category Libary
 * @author zl
 */
abstract class Controller
{
    /**
     * 视图实例对象
     * @var view 
     */
    protected $view;

    /**
     * 构造函数，实例化视图类
     */
    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * 输出内容文本
     * @access protected
     * @param string $templateFile 模版文件名
     */
    protected function display($templateFile = '')
    {
        $this->view->show($templateFile);
    }
    
    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
    }
}
