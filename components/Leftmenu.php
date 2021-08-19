<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class Leftmenu extends Widget{
	public function init(){
    }
    public function run(){ 
    
        return $this->render('leftmenuhtml');
    }
}