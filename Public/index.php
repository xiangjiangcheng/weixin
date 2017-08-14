<?php
/**
 * 微信入口文件
 * 控制跳转
 */
//  echo "dfffffffff";die;
//  echo $_GET['echostr'];die;
// 定义一些常量 
// BASE_PATH 指向项目根目录
// define('BASE_PATH', substr(__DIR__, 0, strrpos(__DIR__, '\\'))); // 指向qigong文件夹 - windows
define('BASE_PATH', substr(__DIR__, 0, strrpos(__DIR__, '/'))); // 指向weixin文件夹 - linux
define('PUBLIC_PATH',__DIR__); // 指向public文件夹 - linux

define('WEBSITE_DOMAIN', 'http://www.qigongplatform.com'); // 网站域名
define('PATH_JS', __DIR__ . '\Js');  // js 路径
define('PATH_BOOTSTRAP', WEBSITE_DOMAIN . '/vendor/twbs/bootstrap/dist');  // js 路径

// 微信
define("TOKEN", "weixin");
define("ACCESS_TOKEN", "C1BYsfs80XVDyEEwjQ-HZWWui99_YOBRwWnWHXfpS8b-8fKrYKqs9PmZieZwzi84Rm_74tiYwpWBnxaXqPvvN7ON_mui2WPCEmkkSyV-oQXvIiQF_LKrrOgpbnPNmaZfWRRfAGARMM");

// 引入自动加载文件
require_once(BASE_PATH . '/Application/Common/MyAutoLoad.class.php');
spl_autoload_register( array('Application\Common\MyAutoLoad','loadprint') );
//另一种写法：spl_autoload_register(  "Application\Common\MyAutoLoad::loadprint"  ); 
// Weixin::writeLogBegin("index.php");die;


Application\Common\MyAutoLoad::run();