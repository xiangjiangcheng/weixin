<?php
namespace Application\Common\Utils;

class Weixin {
    /**
     * 微信校验
     */
    public function validate()
    {
        // echo $_GET['echostr'];exit;
        
        //开始校验
        if (!isset($_GET['echostr'])) {
            Weixin::writeLogBegin("不存在echostr:");
            // true:可以调用相关的处理方法
            return 'success';
        }else{
            
            return $this->valid();
        }
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        // echo $echoStr;die;
        
        //valid signature , option
        if($this->checkSignature()){
             
            return $echoStr;
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

    //    Weixin::writeLogBegin("tmpstr:" . $tmpStr . "||signature" . $signature);die;
        if( $tmpStr == $signature ){
            return true;
            
        }else{
            return false;
        }
    }

    /**
     * 会话
     * @param $url
     * @param null $data
     * @return mixed
     */
    public static function https_request($url, $data = null)
    {
        // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

    

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }

    /**
     * 日志工具
     */
    public static function writeLogBegin($msg = "begin log..............")
    {
        
        // $logFile = date('Y-m-d') . '.txt';
        $msg = date('Y-m-d H:i:s') . ' >>> ' . $msg . "\r\n";
        // echo __DIR__ . "/log.txt";
        // file_put_contents(__DIR__ . "/log.txt", $msg);
        file_put_contents("log.txt", $msg);
    }

    /**
     * 返回消息模板
     */
    public static function module($type)
    {
        $template = ""; // 存放模板
        // type说明： 1:文字消息 2:图片消息 3:语音消息 4:视频.....等

        switch ($type) {
            case 1 : 
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                break;
            case 2 : 
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Image>
                            </xml>";
                break;
            case 3 : 
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Voice>
                            </xml>";
                break;
            case 4 : 
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Video>
                            <MediaId><![CDATA[%s]]></MediaId>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            </Video> 
                            </xml>";
                break;
            case 5 : 
                break;

            
        }
                        
        return $template;
    }

    /**
     * 获取ACCESSTOKEN
     */
    public function getWxAccessToken()
    {
        if($_SESSION['access_token'] && $_SESSION['expire_time'] > time()){
            return $_SESSION['access_token'];
        } else {
            $appid = ''; //填入自己的appid
            $appsecret = '';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
            $res = $this->httpCurl($url); //调用上面提到的curl方法
            $res = json_decode($res, true);
            $access_token = $res['access_token'];
            $_SESSION['access_token'] = $access_token; //将access_token存入缓存，也可用redis、memcache等方式
            $_SESSION['expire_time'] = time()+7000; //获取的access_token的有效期为7200秒，因此缓存的时间应小于7200秒；
            return $access_token();
    }

    /**
     * 获取微信服务器的IP地址
     */
    public function getWxServerIp()
    {
        $access_token = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
        $res = $this->httpCurl($url);
        $arr = json_decode($res,true);
        return $arr;
    }
}


