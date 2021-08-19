<?php

namespace app\controllers;
use Yii;


class EncryptController extends Controller{
    public function actionViewfiles(){
        if(isset($_REQUEST) AND !empty($_REQUEST)){
            $key = "eMulazim";
            $hash=$_REQUEST['key'];
            $string=$_REQUEST['url'];
            $encrypted = str_replace("_","+",$string);
            $ext=strtolower($_REQUEST['ext']);
//           echo $ext; die;
//            $type = '';
            if($ext=="pdf")
            {
            $type="application/pdf";
            }
            else if($ext=="jpg" || $ext=="jpeg") 
            {
            $type="image/jpeg";
            }else if($ext=="png") 
            {
            $type="image/png";
            }
            else if($ext=="mp3")
            {
            $type="audio/mpeg";
            }

            else if($ext=="mp4")
            {
            $type="video/mp4";
            }
            else if($ext=="epub")
            {
            $type="application/epub+zip";
            
            }
//echo $type; die;
            $decrypted = Yii::$app->utility->fileDecrypt($encrypted, $key);
            $url = $file = $decrypted;
            $verify_elib_hash =Yii::$app->utility->getElibHash($url);
            if($hash==$verify_elib_hash){
            header("Content-Type: $type");
            
            readfile($file);
            }
        }
        
    }
}