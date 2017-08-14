<?php
namespace Application\Common;

class MyRoute
{
    public $obj = ""; // controller名
    public $method = ""; // 方法名


    public function __construct($obj, $method)
    {
        $this->obj = 'Application\Home\Controller\\' . $obj;
        $this->method = $method;
    }

    /**
     * 判断函数是否存在，合法，是否可以被调用 
     * 
     */
    public function goto()
    {	
        // $obj = 'utils';
        // $method = 'validate';
        // var_dump(is_callable( array( $obj, $method ) ));die;
        // echo $this->obj . "||" . $this->method;
        if ( is_callable( array( $this->obj, $this->method ) ) ) { 
    
            // 调用指定类下面的指定方法
            $mycalss = new $this->obj();
            // return call_user_func_array(array($mycalss, $this->method), array("three", "fourqw"));
            return call_user_func_array(array($mycalss, $this->method), array());
    	} else {
            return "";
        }

    }

}
