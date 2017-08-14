<?php
/**
  * wechat php test
  */
require("utils.php");
//define your token
define("TOKEN", "weixin");
//define your ACCESS_TOKEN
define("ACCESS_TOKEN", "C1BYsfs80XVDyEEwjQ-HZWWui99_YOBRwWnWHXfpS8b-8fKrYKqs9PmZieZwzi84Rm_74tiYwpWBnxaXqPvvN7ON_mui2WPCEmkkSyV-oQXvIiQF_LKrrOgpbnPNmaZfWRRfAGARMM");
$wechatObj = new wechatCallbackapiTest();


if (!isset($_GET['echostr'])) {
    // $wechatObj->createMenu(); // 自定义菜单
    // $wechatObj->getMenu();
	$wechatObj->responseMsg();
    // utils::writeLogBegin("dfdfdfdfdfd");
}else{
    file_put_contents("test.txt", "第一次校验！"); // 写入test文件,方便查看
    $wechatObj->valid();
    
}

 
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
 
        //valid signature , option
        if($this->checkSignature()){
            utils::writeLogBegin("echostr:".$echoStr);
            echo $echoStr;
            exit;
        }
    }
 
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = file_get_contents("php://input");
        // file_put_contents(__DIR__ . "/test.txt", $postStr);
        utils::writeLogBegin($postStr);
          //extract post data
        if (!empty($postStr)){
                 
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //xml转对象

            //回复用户消息(纯文本格式)	
            $toUser   = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time     = time();
            $msgType  =  'text';  //返回默认为text
            $keyword = $postObj->Content;
            $content = ""; // 返回的内容

            $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
 
            $get_MsgType = strtolower($postObj->MsgType); // 用户请求的类型
            if ($get_MsgType == 'event') {
                $MsgEvent = strtolower($postObj->Event);//获取事件类型  
                
                //如果是关注 subscribe 事件
                if($MsgEvent == 'subscribe'){
                    $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-|||'.$postObj->ToUserName;
                    $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
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
            echo $info;
        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];   
                 
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr ); // 拼接
        $tmpStr = sha1( $tmpStr );
         
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    //创建菜单
    public function createMenu(){
        $data = '{
            "button":[
            {
                "type":"click",
                "name":"首页",
                "key":"home"
            },
            {
                "type":"click",
                "name":"简介",
                "key":"introduct"
            },
            {
                "name":"菜单",
                "sub_button":[
                    {
                    "type":"click",
                    "name":"hello word",
                    "key":"V1001_HELLO_WORLD"
                    },
                    {
                    "type":"click",
                    "name":"赞一下我们",
                    "key":"V1001_GOOD"
                    }]
            }]
        }';
        $ch = curl_init(); // php 创建一个会话
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);  // 执行
        if (curl_errno($ch)) { 
            return curl_error($ch);
        }
        
        curl_close($ch);
        file_put_contents("test.txt", $tmpInfo);
        // echo $tmpInfo;

    }

    //获取菜单
    public function getMenu(){
        $data =  file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
        file_put_contents("test.txt", $data);
    }

    //删除菜单
    public function deleteMenu(){
        return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
    }

    //图文消息
    public function transmitNews($object, $arr_item, $flag = 0)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <Content><![CDATA[]]></Content>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>
                        $item_str</Articles>
                        <FuncFlag>%s</FuncFlag>
                    </xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $flag);
        
        return $resultStr;
    } 
}
 
?>