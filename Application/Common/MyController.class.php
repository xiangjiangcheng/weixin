<?php
namespace Application\Common;

use Application\Common\Utils\Weixin;

/**
 * 公共的controller
 */
class MyController
{
    protected $template = ""; // 返回的模板类型
    protected $postStr = ""; // 微信端xml数据
    protected $postObj = array(); // 得到的对象
    protected $toUser = "";
    protected $fromUser = "";
    protected $time = "";
    protected $msgType =  'text';  //返回默认为text
    protected $keyword = "";
    protected $content = ""; // 返回的内容

    function __construct()
    {
        $this->postStr = file_get_contents("php://input");
        Weixin::writeLogBegin($this->postStr);

        

        if (!empty($this->postStr)){
            $this->postObj = simplexml_load_string($this->postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //xml转对象
            //回复用户消息(纯文本格式)	
            $this->toUser   = $this->postObj->FromUserName;
            $this->fromUser = $this->postObj->ToUserName;
            $this->time     = time();
            $this->msgType  =  strtolower($this->postObj->MsgType); // 用户请求的类型
            $this->keyword = $this->postObj->Content;
            $this->content = ""; // 返回的内容

            
        }
        

    }

}
