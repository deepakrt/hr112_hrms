<?php

//phpinfo();

date_default_timezone_set('Asia/Kolkata');
// comment out the following two lines when deployed to production

/*******************Security Code*****************************/

$_POST  = strip($_POST);
//session_regenerate_id($deleteOldSession);
function strip($string, $allowed_tags = NULL)
 {
    if (is_array($string))
    {
        foreach ($string as $k => $v)
        {
            $string[$k] = strip($v, $allowed_tags);
        }
        return $string;
    }
     return strip_tags(trim($string), $allowed_tags);
 }
die("123");
if(isset($_SERVER['HTTPS'])) { $url = 'https://' . $_SERVER['SERVER_ADDR'].'/erss'; }
	else { $url = 'http://' . $_SERVER['SERVER_ADDR'].'/erss'; }
  print_r($_SERVER);die;
//echo $url;
$q_str=strtolower($_SERVER['QUERY_STRING']);
if( strpos( $q_str, '%3c' ) !== false || strpos( $q_str, '%3e' ) !== false) {
    header("Location: $url");die;
}
if( strpos( $q_str, 'alert' ) !== false || strpos( $q_str, 'onmouseover' ) !== false || strpos( $q_str, 'script' ) !== false || strpos( $q_str, 'confirm' ) !== false || strpos( $q_str, '"' ) !== false || strpos( $q_str, "'" ) !== false) {
    header("Location: $url");die;
}
$rURI=$_SERVER['REQUEST_URI'];
if( strpos( $rURI, '.php' ) !== false || strpos( $rURI, '.htm' ) !== false || strpos( $rURI, '.html' ) !== false || strpos( $rURI, '.aspx' ) !== false || strpos( $rURI, '.jsp' ) !== false) {
    header("Location: $url");die;
}
/*******************Security Code*****************************/
if(isset($_GET['testtt'])){
 defined('YII_DEBUG') or define('YII_DEBUG', true);
  error_reporting(E_ALL);
}else{
defined('YII_DEBUG') or define('YII_DEBUG', true); 
 error_reporting(E_ALL);
}
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');
require(__DIR__ . '/other_files/constant.php');

(new yii\web\Application($config))->run();

