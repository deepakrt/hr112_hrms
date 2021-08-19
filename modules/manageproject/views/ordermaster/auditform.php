<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\date\DatePicker;
//use yii\widgets\MaskedInput;
//use frontend\models\Auditmaster;
//use frontend\models\Manpowermapping;
//use frontend\models\Investigator;
//use frontend\models\Ordermaster;
//use common\models\User;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Auditmaster */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
       function sessionid()
        {        
           $session = Yii::$app->session; 
           $session->open();
           return  Yii::$app->session->getId();            
        }              
        function updated()
        {
            return Yii::$app->formatter->asDatetime(time());
        }
        function userid()
        {
           return Yii::$app->user->id;
           
        }
    ?>  



    <div class="auditmaster-form">

        <?php $form = ActiveForm::begin(['fieldConfig' => ['labelOptions' => ['class' => 'client-form-label'], 'inputOptions' => ['class' => 'client-form-input'],]]);  ?>
        <div class="det-form">    
                <?php 
                    if (isset($_SESSION['prjsession'])) {                        
                        echo '<p class="record-heading">'.Yii::$app->projectcls->SelectOrder($_SESSION['prjsession'])->projectname. '</p>';
                        echo $form->field($model, 'orderid')->hiddenInput(['value'=>$_SESSION['prjsession']])->label(FALSE);                        
                    }
                    else if(Yii::$app->request->get('id') !=NULL){                        
                        echo '<p class="record-heading">'.$this->params['order']->projectname . '</p>';                                 
                        echo $form->field($model, 'orderid')->hiddenInput(['value'=> $this->params['order']->id])->label(FALSE);
                  
                    } else{                        
                        echo $form->field($model, 'orderid')->DropDownList(ArrayHelper::map($this->params['project'],'orderid','ordermaster.projectname','ordermaster.fundingagency'),
                            ['prompt'=>'Please select',])->Label('Select the Project');
                    }
                ?>
                
                

                <?= $form->field($model, 'audittype')->dropDownList(['GIGW Audit' => 'GIGW Audit', 'Security Audit'=>'Security Audit'], ['prompt'=>'Please select'])->Label('Type of Audit') ?>

                <?= $form->field($model, 'startdate')->widget(DatePicker::classname(), [
                        'name' => 'dp_3',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => '23-Feb-1982',     
                        'options' => ['class' => 'client-form-input-date'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy/mm/dd',                
                        ]
                    ])->label('Start Date of GIGW/Security Audit'); ?>

                <?= $form->field($model, 'auditagency')->textArea(['maxlength' => true, 'rows' => 3])->Label('Auditing Agency Name & Address') ?>

                <?= $form->field($model, 'auditreport')->textArea(['maxlength' => true, 'rows' => 3])->Label('Audit Report Summary') ?>

                <?= $form->field($model, 'reportdate')->widget(DatePicker::classname(), [
                        'name' => 'dp_3',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => '23-Feb-1982',       
                        'options' => ['class' => 'client-form-input-date'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy/mm/dd',                
                        ]
                    ])->label('Audit Report receiving date'); ?>

                <?= $form->field($model, 'status')->dropDownList(['Closed' => 'Closed', 'In Progress'=>'In Progress'], ['prompt'=>'Please select'])->Label('Status') ?>

                <?= $form->field($model, 'remarks')->textArea(['maxlength' => true, 'rows' => 6]) ?>

                <?= $form->field($model, 'activeuser')->hiddenInput(['value' => userid()])->label(FALSE) ?>

                <!--<?//= $form->field($model, 'deleted')->textInput() ?>-->

                <?= $form->field($model, 'sessionid')->hiddenInput(['maxlength' => true, 'value' => sessionid()])->label(FALSE) ?>

                <!--<?//= $form->field($model, 'updatedon')->textInput() ?>-->
        </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>                    
                    <?php if ($_SESSION['userrole'] == 'admin' || $_SESSION['userrole'] == 'editor' || $_SESSION['userrole'] == 'premium') {?>
                        <?= Html::a(Yii::t('app', 'Cancel'), ['site/projectdetail'], ['class' => 'btn btn-default']) ?>
                    <?php } else { ?>
                        <?= Html::a(Yii::t('app', 'Cancel'), ['site/index'], ['class' => 'btn btn-default']) ?>
                    <?php }  ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>       