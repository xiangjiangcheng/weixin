<?php
namespace Application\Home\Controller;

use Application\Common\MyController;
use Application\Common\Utils\Weixin;

class Text extends MyController
{
    public function test()
    {
        // 
         $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
        $content = $this->keyword;
        $result = sprintf($template, $this->toUser, $this->fromUser, $this->time, $this->msgType, $content);
        // $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
      
    
        return $result;
        // echo "text/test";
    }

}