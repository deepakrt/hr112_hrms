<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class Topmenu extends Widget{
    public function init(){
    }
    public function run(){ 
    
        return $this->render('topmenuhtml');
    }
}