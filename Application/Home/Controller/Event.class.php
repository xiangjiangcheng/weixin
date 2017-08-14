<?php
namespace Application\Home\Controller;

use Application\Common\MyController;
use Application\Common\Utils\Weixin;

class Event extends MyController
{
    public function subscribe()
    {
        // 
         $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
        $msgType = "text";
        $content  = "欢迎关注我们的微信公众账号" . $this->toUser;
        $result = sprintf($template, $this->toUser, $this->fromUser, $this->time, $msgType, $content);
         Weixin::writeLogBegin($result);
        return $result;
    }

}