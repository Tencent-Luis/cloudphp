<?php
/**
 * Description of Exception
 * cloudphp异常类
 * @author luis
 * @time 2014-11-19
 */
class Lib_Exception
{
    private static $_line_no;
    private static $_file;
    private static $_message;
    private static $_trace;
    
    /**
     * 异常信息
     * @param string $message 异常信息
     */
    public static function Exception_Message($message = '')
    {
        $exception = new Exception();
        self::$_line_no = $exception->getLine();
        self::$_file = $exception->getFile();
        self::$_message = $message;
        self::$_trace = $exception->getTraceAsString();
        
        self::error_html();
    }
    
    /**
     * error的html样式
     */
    private static function error_html()
    {
        $html = "<div style='font-weight: bold;width: auto; margin-left: 15%;'>";
        $html .= '行号：' . self::$_line_no . '<br/>';
        $html .= '文件：' . self::$_file . '<br/>';
        $html .= '信息：' . self::$_message . '<br/>';
        $html .= "<div style='background-color: #cccccc; width: 80%; height: auto; font-weight: normal;'>" . self::$_trace . "";
        $html .= "</div></div>";
        
        echo $html;
    }
}
