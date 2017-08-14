<?php
namespace Application\Common;

/**
* 自动加载类 
*/
class MyAutoLoad {
    public static function loadprint( $class ) {
        
        $file = BASE_PATH . '\\' . $class . '.class.php';  
        $file = str_replace('\\', "/", $file);  // 转换格式
        if (is_file($file)) {   
            require_once($file);  
        } 
    }

    public static function run()
    {   
        //调用微信验证
        $handle = new HandleRequest();
        $handle->togo();
    }
} 
