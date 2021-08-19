<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class Ftstopmenu extends Widget{
    public function init(){
    }
    public function run(){ 
    
        return $this->render('ftstopmenuhtml');
    }
}