<?php
//use yii\helpers\Url;

$this->title = 'Admin Services';
$this->params['breadcrumbs'][] = $this->title; 
?>
      
<div class="row-eq-height">
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        <div class="tools">TOOLS</div>
        <div class="row breadcrumb">&nbsp;</div>            
        <?php echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1');?> 
    </div>    
    <div class="col-lg-10 right-header">
        <div class="row content-bar text-justify" >            
            <?php //echo \Yii::$app->view->render('@app/views/layouts/mainbar');?>
        </div>
        <div class="row breadcrumb">
            <div class="col-lg-8" style="padding: 0px; margin: 0px;">
                <?php  echo \Yii::$app->view->render('@app/views/layouts/breadcrumb');?>
            </div>
            <div class="col-lg-4 prjsession">
                <?php $session = Yii::$app->session; ?>
                <?php $form = \yii\widgets\ActiveForm::begin(['action' =>['cdacdept/create'], 'id' => 'city-form', 'method' => 'post','fieldConfig' => ['labelOptions' => ['class' => 'client-form-label'], 'inputOptions' => ['class' => 'client-form-input'],]]); ?>
                        <?= yii\helpers\Html::dropDownList('id1', null, $this->params['roleasgn'],
                            [
                                'prompt'=>'--Switch Role--',
                                'class' => 'text-centre',
                                'options' => [$_SESSION['userrole']=>array('selected'=>true)],
                                'onchange'=>'
                                    $.post( "'.Yii::$app->urlManager->createUrl('cdacdept/userrole?id=').'"+$(this).val(), function( data ) {});
                            ']); ?>
                                    <!--<span class="col-lg-6">
                                        select the Department
                                        <?/*= yii\helpers\Html::dropDownList('id', null, yii\helpers\ArrayHelper::map(Yii::$app->projectcls->SelectCdacdept(), 'id', 'subdept', 'deptname'),
                                            ['prompt'=>'Select Department',
                                            'onchange'=>'
                                                 $.post( "'.Yii::$app->urlManager->createUrl('cdacdept/lists?id=').'"+$(this).val(), function( data ) {                                         
                                            });
                                       '])*/ ?>
                                    </span>-->
                <?php  \yii\widgets\ActiveForm::end();?>
            </div>
        </div>   
        <div>
            
        </div>
        
        <div class="mintable">        
            
            <!--<p class="record-heading">
                
                <span style="float:right"></span>
            </p>--> 
            
        </div>
    </div>
</div>