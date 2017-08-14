<?php
namespace Application\Common;

use Application\Common\Utils\Weixin;
/**
 * 处理微信请求 
 * 控制方法跳转
 */
class HandleRequest
{
    /**
     * 先校验 
     * 再跳转
     * 最后将结果返回
     */
    public function togo()
    {
        // 调用工具类里面的校验方法进行校验
        $weixin = new Weixin();
        $msg = $weixin->validate();
        if ($msg == 'success') {
            // 得到微信端请求，并进行跳转
            $this->responseMsg();
            
        } else {
            // 第一次 原路返回echostr
            echo $msg;
            exit;
        }

    }

    public function responseMsg()
    {   
        $obj = "Text";
        $method = "test";

        $postStr = file_get_contents("php://input");
        // file_put_contents(__DIR__ . "/test.txt", $postStr);
          //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //xml转对象
            $get_MsgType = strtolower($postObj->MsgType); // 用户请求的类型
           
            
            if ($get_MsgType == 'event') {
                $MsgEvent = strtolower($postObj->Event);//获取事件类型  
                 
                //如果是关注 subscribe 事件
                if($MsgEvent == 'subscribe'){
                    
                    $obj = "Event";
                    $method = "subscribe";
                    // Weixin::writeLogBegin($method);die;
                } else if ($MsgEvent == 'unsubscribe') {
                    $content = '欢迎下次使用！';
                    $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                } else if ($MsgEvent=='click') {
                    //点击事件  
                    $EventKey = $postObj->EventKey;//菜单的自定义的key值，可以根据此值判断用户点击了什么内容，从而推送不同信息  
                    switch($EventKey)
                    {
                    case "home" :
                        //要返回相关内容
                        $content  = '点击了首页，we are 伐木累！';
                        break;
                    case "introduct" :
                        //要返回相关内容
                        $content  = '点击了简介：学习微信开发的第二天，该公众号是一个微信测试账号！';
                        break;
                    case "V1001_HELLO_WORLD" :
                        $content  = '点击了hello world！';
                        //要返回相关内容
                        break;
                    case "V1001_GOOD" :
                        $content  = '点击了赞我们一下！';
                        //要返回相关内容
                        break;
                    }
                    $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                }  
            } else if ($get_MsgType == 'text' && trim($postObj->Content)!='图文') {
                // 文本
                $content = $keyword;
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            } else if ($get_MsgType == 'image') {
                // 图片
                $msgType = 'image'; 
                // $media_id = $postObj->MediaId;
                //$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=" . ACCESS_TOKEN . "&media_id=" . $media_id;
                $content = $postObj->MediaId;
                $template = utils::module(2);
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                // utils::writeLogBegin($content);
            } else if( $get_MsgType == 'text' && trim($postObj->Content)=='图文' ){
                // 图文数据
                $arr = array(
                    array(
                        'Title'=>'imooc',
                        'Description'=>"imooc is very cool",
                        'PicUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                        'Url'=>'http://www.imooc.com',
                    ),
                    array(
                        'Title'=>'hao123',
                        'Description'=>"hao123 is very cool",
                        'PicUrl'=>'https://www.baidu.com/img/bdlogo.png',
                        'Url'=>'http://www.hao123.com',
                    ),
                    array(
                        'Title'=>'qq',
                        'Description'=>"qq is very cool",
                        'PicUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                        'Url'=>'http://www.qq.com',
                    ),
                );
                $info = $this->transmitNews($postObj, $arr);

            }
            // $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            // echo $info;
        }else {
            echo "";
            exit;
        }

        
        // 跳转到相关的控制方法
        $route = new MyRoute($obj, $method);
        echo $route->goto();
           
        

        
    }


}