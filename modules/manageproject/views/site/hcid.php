<?php

/* @var $this yii\web\View */
use frontend\models\Projects;
use frontend\models\Manpower;
use frontend\models\Manpowermapping;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $this->params['sdept'];
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/site/index']];
//$link = explode('?', Yii::$app->request->referrer);
//$link = explode('/', $link[0]);



//if($link[sizeof($link)-1] =='deptbrief'){ 
if(sizeof($this->params['dept'])>1){
    $this->params['breadcrumbs'][] = ['label' => $this->params['dpt'] , 'url' => ['/site/deptbrief', 'id' =>  Yii::$app->request->get('id')]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-clientdetail row-eq-height">
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        <div class="tools">Tools</div>
        <div class="row breadcrumb ">&nbsp;</div>
        <span class="ed_sidebar">Divisional Dashboard</span> <br/>        
    </div>
    <div class="col-lg-10 right-header">
        <div class="row content-bar text-justify" >            
            <?php echo \Yii::$app->view->render('@app/views/layouts/mainbar');?>
        </div>
        <div class="row breadcrumb">
            <div class="col-lg-12" style="padding: 0px; margin: 0px;">
                <?php  echo \Yii::$app->view->render('@app/views/layouts/breadcrumb');?>
            </div>
            <!--<div class="col-lg-12 prjsession">
                <div class="alert-info" role="alert">
                    <?//= Yii::$app->session->getFlash('success'); ?>
                </div>                
            </div>-->
        </div>
        <div class="row ">            
            <div class="col-lg-12 ">
                <div class="col-lg-12 text-left client-form-label" style="background-color: d6e0df;"> 
                    <div style="margin: 5px 5px 15px 5px;">
                        <div style="font-size: medium; font-weight: bold" class="text-uppercase">
                            <?= $this->params['subDept']?>
                            <i class="text-capitalize" style="border:2px solid #76bebb; background-color:#d6e0df; margin: 2px 2px 2px 2px">
                                <?= $this->params['shortName']   ?>
                            </i>
                        </div>
                        <!--HUMAN COMPUTER INTERFACE DIVISION <i style="border:2px solid green; background-color: greenyellow; margin: 2px 2px 2px 2px">HCID</i>-->
                    </div>
                    <div style="font-size:12px; margin: 5px 5px 15px 5px">
                        <div class="col-lg-4">
                            <div class="col-lg-2">
                                <?= Html::img('@web/uploads/Images/manpower.svg', ['class' => 'director-manpower-icons']);?>
                            </div>
                            <div class="col-lg-10">
                                <div class="col-lg-5">MANPOWER</div>
                                <div class="col-lg-7"><?=$this->params['manpower'];?></div>
                                    
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Regular : <?=$this->params['regular']; ?>
                                </div>                                
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Grade Based : <?=$this->params['grade']; ?>
                                </div>
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Contractual : <?=$this->params['contractual']; ?>                                    
                                </div>
                            </div>                            
                        </div>
                        <div class="col-lg-4">
                            <div class="col-lg-2"><?= Html::img('@web/uploads/Images/projects.svg', ['class' => 'director-projects-icons']);?></div>
                            <div class="col-lg-10">
                                ACTIVE PROJECTS
                                <div style="font-size: smaller;">
                                    Total : <?=$this->params['activeprojects']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-lg-2"><?= Html::img('@web/uploads/Images/profit.svg');?></div>
                            <div class="col-lg-10">
                                <div class="col-lg-12">FINANCIAL</div>
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Salary : <?=$this->params['salary']; ?>
                                </div>
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Projects (2020): <?=$this->params['business20']; ?>
                                </div>
                                <div class="col-lg-12"  style="font-size: smaller;">
                                    Projects (2019): <?=$this->params['business19']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                
                <div class="row text-left">
                    <?php echo yii\bootstrap\Collapse::widget();?>
                    <div class="col-lg-12" style="margin-top: 20px;">
                        <!--<p class="record-heading">MANPOWER</p>-->
                        <table class="table table-responsive table-condensed" style="border-collapse:collapse; ">

                            <thead style="word-wrap: break-word;">
                                <tr style="display:table; width:100%; table-layout:fixed; color: #2c3e50; background-color: #76bebb;">
                                    <th>NAME / DESIGNATION</th>                                    
                                    <th>QUALIFICATION</th>
                                    <th>SKILL SET</th>    
                                    <th></th>
                                    <th class="text-right">OCCUPANCY</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody style="display:block; height:350px; overflow-y:scroll; overflow-x:auto;">
                                <?php 
                                $i=0;
                                foreach ($this->params['manlist'] as $manlist){ ?>
                                <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle" style="background: #d6e0df; font-size: small; display:table; width:100%; table-layout:fixed;">

                                        <td class="col-lg-2"><?=Html::a($manlist->name, ['/manpower/view', 'id' => $manlist->id]); ?> <br/><i><?=$manlist->designation->designation ?></i></td>                                        
                                        <td class="col-lg-2"><?=Yii::$app->projectcls->Qualification($manlist->id) ?></td>
                                        <td class="col-lg-5" colspan="2">
                                            <?=Yii::$app->projectcls->Technology($manlist->id) ?>
                                        </td>                                        
                                        <td  class="col-lg-2 text-center">
                                            <?php 
                                                $sal =0;
                                                $prj = '';
                                                for($k=0; $k<sizeof(Yii::$app->projectcls->mapEd($manlist->id)); $k++){
                                                    if(Yii::$app->projectcls->mapEd($manlist->id)[$k]!=null){
                                                        if(date('Y-m-d',strtotime(date('Y-m-d H:i:s'))) > date('Y-m-d',strtotime(Yii::$app->projectcls->mapEd($manlist->id)[$k]->project->expectedenddate))) {                                                            
                                                            $sal+= 0;
                                                        }else{   
                                                            $sal += Yii::$app->projectcls->mapEd($manlist->id)[$k]->salary ;                                                           
                                                        }
                                                    }
                                                }
                                                if(Yii::$app->projectcls->mapTraining($manlist->id) != NULL){
                                                    for($k=0; $k<sizeof(Yii::$app->projectcls->mapTraining($manlist->id)); $k++){
                                                        if(strtotime(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['end_date']) > strtotime(date("Y/m/d"))){ 
                                                            $sal += Yii::$app->projectcls->mapTraining($manlist->id)[$k]['salary'];
                                                        }
                                                    }
                                                }
                                                echo $sal .'%';
                                            ?>
                                        </td>
                                        <td class="col-lg-1">
                                            <?php 
                                                
                                                    yii\bootstrap\Modal::begin([
                                                       'header' => 'modal header',
                                                       'toggleButton' => [
                                                              'tag' => 'a', 
                                                              'label' => Yii::t('category', '<span class="glyphicon glyphicon-eye-open"></span>'), 
                                                              'href'=>'#'.$i
                                                   ]]); 
                                                
                                            ?>
                                            <!--<button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>-->
                                    </tr>                                    
                                    <tr style="font-size: small; display:table; width:100%; table-layout:fixed;">
                                        <td colspan="12" class="hiddenRow">
                                            <div id="<?=$i;?>" class="accordian-body collapse" style="margin-top:10px" > 
                                                <table class="table" style="background-color: #e6e6e6">                                                     
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-top: 0px;"><?php echo Html::img(Yii::$app->request->BaseUrl.'/uploads/pics/' . $manlist->empcode.'.JPG' , ['class'=>'image']) ?></td>
                                                            <td style="padding-top: 0px;">
                                                                <table class="table" style="background-color: #e6e6e6; color: #2c3e50;"> 
                                                                    <thead style="background-color: #cfd8df;">                                                                        
                                                                    <td class="col-lg-3"> <b>Technology Used</b></td>
                                                                    <td class="col-lg-7"><b>Project Name</b></td>
                                                                    <td class="col-lg-2 text-center"><b>Occupancy</b></td>                                                        
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php for ($j =0; $j<sizeof(Yii::$app->projectcls->mapEd($manlist->id)); $j++){?>
                                                                            <tr>                                                                                
                                                                                <td class="col-lg-3">
                                                                                    <?php 
                                                                                        if(Yii::$app->projectcls->mapEd($manlist->id)[$j]!=null){
                                                                                            echo Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->projecttechnology;
                                                                                        } else {
                                                                                            echo '-';
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="col-lg-7">
                                                                                    <?php
                                                                                        if(Yii::$app->projectcls->mapEd($manlist->id)[$j]!=null){
                                                                                            echo Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->orderdetail->projectname .'<br/>';
                                                                                            echo '<div class="col-lg-6 small"><b>Start Date:</b> '. Yii::$app->formatter->asDate(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->projectstartdate) .'</div>';
                                                                                            if(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->actualcompletiondate == null){
                                                                                                if(strtotime(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->expectedenddate) < strtotime(date("Y/m/d"))){
                                                                                                    echo '<div class="col-lg-6 small text-danger"><b>Expected End Date:</b> '. Yii::$app->formatter->asDate(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->expectedenddate) .'</div>';
                                                                                                } else{
                                                                                                    echo '<div class="col-lg-6 small"><b>Expected End Date:</b> '. Yii::$app->formatter->asDate(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->expectedenddate) .'</div>';
                                                                                                }
                                                                                            } else {
                                                                                                echo '<div class="col-lg-6 small"><b>Completion Date:</b> '. Yii::$app->formatter->asDate(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->actualcompletiondate) .'</div>';
                                                                                            }
                                                                                        } else {
                                                                                            echo '-';
                                                                                        } echo '<br/>';
                                                                                    ?></td>
                                                                                <td class="col-lg-2 text-center">
                                                                                    <?php 
                                                                                        if(Yii::$app->projectcls->mapEd($manlist->id)[$j]!=null){
                                                                                            if(date('Y-m-d',strtotime(date('Y-m-d H:i:s'))) > date('Y-m-d',strtotime(Yii::$app->projectcls->mapEd($manlist->id)[$j]->project->expectedenddate))) {
                                                                                                echo '0 %';
                                                                                            }else{
                                                                                                echo Yii::$app->projectcls->mapEd($manlist->id)[$j]->salary .'%';
                                                                                            }
                                                                                        } else {
                                                                                            echo '-';
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="col-lg-1"></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                                <?php if(Yii::$app->projectcls->mapTraining($manlist->id)!=null){ ?>
                                                                    <table class="table" style="background-color: #e6e6e6; color: #2c3e50;"> 
                                                                        <thead style="background-color: #cfd8df;">                                                                        
                                                                            <td class="col-lg-3"> <b>Training</b></td>
                                                                            <td class="col-lg-7"><b>Course Name</b></td>
                                                                            <td class="col-lg-2 text-center"><b>Occupancy</b></td>                                                        
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php for ($k =0; $k<sizeof(Yii::$app->projectcls->mapTraining($manlist->id)); $k++){
                                                                                if(strtotime(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['end_date']) > strtotime(date("Y/m/d"))){ ?>
                                                                                <tr>                                                                                
                                                                                    <td class="col-lg-3">
                                                                            <?php                                             
                                                                                        if(Yii::$app->projectcls->mapTraining($manlist->id)[$k]!=null){
                                                                                            echo Yii::$app->projectcls->mapTraining($manlist->id)[$k]->course_name;
                                                                                        } else {
                                                                                            echo '-';
                                                                                        }
                                                                            ?>
                                                                                    </td>
                                                                                    <td class="col-lg-7">

                                                                                        <?php
                                                                                            if(Yii::$app->projectcls->mapTraining($manlist->id)[$k]!=null){
                                                                                                echo \frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['technology_code'] .'<br/>';
                                                                                                echo '<div class="col-lg-12 small"><b>Course Duration:</b> '. Yii::$app->formatter->asDate(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['start_date']) .' - '. Yii::$app->formatter->asDate(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['end_date']) .'</div>';
                                                                                                echo '<div class="col-lg-12 small"><b>Timings:</b> '. Yii::$app->formatter->asTime(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['start_time']) .' - '. Yii::$app->formatter->asTime(\frontend\facade\Edudata::getTraining(Yii::$app->projectcls->mapTraining($manlist->id)[$k]->tpm_id)[0]['end_time']).'</div>';
                                                                                            } else {
                                                                                                echo '-';
                                                                                            } echo '<br/><br/>';
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center">
                                                                                        <?php 
                                                                                            if(Yii::$app->projectcls->mapTraining($manlist->id)[$k]!=null){
                                                                                                echo Yii::$app->projectcls->mapTraining($manlist->id)[$k]->salary .'%';                                                                
                                                                                            } else {
                                                                                                echo '-';
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1"></td>
                                                                                </tr>
                                                                            <?php 
                                                                                }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        
                                                            <tr>
                                                                <td colspan="6" class="panel-footer text-right">
                                                                    <?php echo yii\helpers\Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/shiftmanpower/create', 'id' => $manlist->id]);?>
                                                                    <?php 
                                                                        echo yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/shiftmanpower/left', 'id' => $manlist->id], [
                                                                            'class' => '',
                                                                            'data' => [
                                                                                'confirm' => 'Are you absolutely sure, this manpower has left C-DAC ?',
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                                
                                            </div> 
                                        </td>
                                    </tr>
                                <?php $i++; } ?>
                                
                                <!--<tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                        <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                                        <td>OBS Name</td>
                                    <td>OBS Description</td>
                                    <td>hpcloud</td>
                                    <td>nova</td>
                                  <td> created</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="hiddenRow"><div id="demo2" class="accordian-body collapse">Demo2</div></td>
                                </tr>
                                <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                    <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                                    <td>OBS Name</td>
                                    <td>OBS Description</td>
                                    <td>hpcloud</td>
                                    <td>nova</td>
                                  <td> created</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="hiddenRow"><div id="demo3" class="accordian-body collapse">Demo3</div></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row text-left">                    
                    <div class="col-lg-12" style="margin-top: 20px; ">
                        <p class="record-heading">COST MATRIX</p>
                        <table class="table table-responsive table-condensed" style="border-collapse:collapse; ">

                            <thead style="word-wrap: break-word;">
                                <tr style="display:table; width:100%; table-layout:fixed; background:#d6e0df">
                                    <th  class="col-lg-6">PROJECTS</th>                                    
                                    <th  class="col-lg-2">COST</th>
                                    <th class="col-lg-2">RECEIVED</th>
                                    <th class="col-lg-1">PENDING</th>                                    
                                    <th class="col-lg-1">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody style="display:block; height:350px; overflow-y:scroll; overflow-x:auto;">
                                <?php 
                                $i=0;
                                foreach ($this->params['projectlist']  as $project){ ?>
                                <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle" style="background: #bdd7d6; font-size: small; display:table; width:100%; table-layout:fixed;">

                                        <td class="col-lg-6">
                                            <?=Html::a($project->orderdetail->projectname, ['/projects/view', 'id' => $project->id]); ?> <br/>
                                            <i>(<?=$project->orderdetail->clientdetail->deptName ?>)</i><br/>
                                            <div class="col-lg-6 small"><b>Start Date:</b> <?php echo Yii::$app->formatter->asDate($project->projectstartdate) ?></div>
                                            <?php
                                                if($project->actualcompletiondate == null){
                                                    if(strtotime($project->expectedenddate) < strtotime(date("Y/m/d"))){
                                                        echo '<div class="col-lg-6 small text-danger"><b>Expected End Date:</b> '. Yii::$app->formatter->asDate($project->expectedenddate) .'</div>';
                                                    } else{
                                                        echo '<div class="col-lg-6 small"><b>Expected End Date:</b> '. Yii::$app->formatter->asDate($project->expectedenddate) .'</div>';
                                                    }
                                                } else {
                                                    echo '<div class="col-lg-6 small"><b>Completion Date:</b> '. Yii::$app->formatter->asDate($project->actualcompletiondate) .'</div>';
                                                }
                                            ?>
                                        </td>                                        
                                        <td class="col-lg-2"><?=Html::a(Yii::$app->formatter->asCurrency($project->orderdetail->amount, 'INR'), ['/ordermaster/view', 'id' => $project->orderdetail->id]) ?></td>
                                        <td class="col-lg-2"><?=Yii::$app->formatter->asCurrency(Yii::$app->projectcls->TotalAmountReceived($project->orderdetail->id), 'INR') ?></td>
                                        <td class="col-lg-1"><?=Yii::$app->formatter->asCurrency(Yii::$app->projectcls->AmountPending($project->orderdetail->id), 'INR')?></td>                                        
                                        <td class="col-lg-1 text-center">
                                            <?php 
                                                
                                                    yii\bootstrap\Modal::begin([
                                                       'header' => 'modal header',
                                                       'toggleButton' => [
                                                              'tag' => 'a', 
                                                              'label' => Yii::t('category', '<span class="glyphicon glyphicon-eye-open"></span>'), 
                                                              'href'=>'#c'.$i
                                                   ]]); 
                                                
                                            ?>                                            
                                    </tr>                                     
                                    <tr style="font-size: small; display:table; width:100%; table-layout:fixed;">
                                        <td colspan="12" class="hiddenRow">
                                            <div id="c<?=$i;?>" class="accordian-body collapse"> 
                                                <table class="table">  
                                                    <thead>
                                                        <tr>
                                                            <td class="col-lg-6">Bills</td>
                                                            <td class="col-lg-6">Receipt</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                                        
                                                        <?php for ($j =0; $j<sizeof(Yii::$app->projectcls->EdBillmaster($project->orderdetail->id)); $j++){?>
                                                            <tr>
                                                                <td class="col-lg-6">  
                                                                    <?php $a =Yii::$app->projectcls->EdBillmaster($project->orderdetail->id)[$j]->id ?> 
                                                                    <b>Bill No:</b> <?=Yii::$app->projectcls->EdBillmaster($project->orderdetail->id)[$j]->billnumber ?> <br/>
                                                                    <b>Date:</b> <?=Yii::$app->formatter->asDate(Yii::$app->projectcls->EdBillmaster($project->orderdetail->id)[$j]->billdate) ?> <br/>
                                                                    <b>Amount:</b><?=Yii::$app->formatter->asCurrency(Yii::$app->projectcls->EdBillmaster($project->orderdetail->id)[$j]->billamount, 'INR') ?> <br/>
                                                                </td>
                                                                <td class="col-lg-6">
                                                                    <?php if(Yii::$app->projectcls->EdReceipt($a) != NULL){?>
                                                                        <b>Date:</b><?=Yii::$app->projectcls->EdReceipt($a)->date ?><br/>
                                                                        <b>Mode of Payment:</b><?=Yii::$app->projectcls->EdReceipt($a)->mediumofpayment ?><br/>
                                                                        <b>Amount:</b> <?=Yii::$app->formatter->asCurrency(Yii::$app->projectcls->EdReceipt($a)->amount, 'INR');?><br/>                                                                    
                                                                    <?php } else {
                                                                        print_r('-');
                                                                    }?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?> 
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                            </div> 
                                        </td>
                                    </tr>
                                <?php $i++; } ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--<div class="row">
                    
                    <div style="position:relative;" class="gantt col-lg-12" id="GanttChartDIV"></div>
                    
                    <script>
                        var g = new JSGantt.GanttChart('g',document.getElementById('GanttChartDIV'), 'month');

                        g.setShowRes(0); // Show/Hide Responsible (0/1)
                        g.setShowDur(0); // Show/Hide Duration (0/1)
                        g.setShowComp(0); // Show/Hide % Complete(0/1)
                        g.setCaptionType('None');  // Set to Show Caption (None,Caption,Resource,Duration,Complete)
                        g.setShowStartDate(1); // Show/Hide Start Date(0/1)
                        g.setShowEndDate(1); // Show/Hide End Date(0/1)
                        g.setDateInputFormat('mm/dd/yyyy')  // Set format of input dates ('mm/dd/yyyy', 'dd/mm/yyyy', 'yyyy-mm-dd')
                        g.setDateDisplayFormat('dd/mm/yyyy') // Set format to display dates ('mm/dd/yyyy', 'dd/mm/yyyy', 'yyyy-mm-dd')
                        g.setFormatArr("day","week","month","quarter") // Set format options (up to 4 :                 

                        <!--<?php 
                            /*$k=0;
                            foreach ($this->params['projectlist']  as $project){
                                echo "g.AddTaskItem(new JSGantt.TaskItem(1,'". $project->orderdetail->projectname ."', '3/24/2008', '3/25/2008', 'ffff00', '', 0, '', 20, 0, 0, 0,0))";                                
                                $k++;
                        }*/ ?>
                        g.AddTaskItem(new JSGantt.TaskItem(1,   'HTML Shell',    '3/24/2008', '3/25/2008', 'ffff00', '', 0, '',    20, 0, 0, 0,0));
                        g.AddTaskItem(new JSGantt.TaskItem(2,   'Shell',    '3/24/2008', '3/25/2008', 'ffff00', '', 0, '',    220, 0, 0, 0,0));
                        g.AddTaskItem(new JSGantt.TaskItem(3,  'Chart Object',   '2/10/2008', '2/10/2008', 'ffff00', '', 0, '',  100, 0, 0, 0, 0));

                        //g.AddTaskItem(new JSGantt.TaskItem(1,   'Define Chart API',     '',          '',          'ff0000', 'http://google.com', 0, 'Brian',     0, 1, 0, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(11,  'Chart Object',         '2/20/2008', '2/20/2008', 'ff00ff', 'http://www.yahoo.com', 1, 'Shlomy',  100, 0, 1, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(12,  'Task Objects',         '',          '',          '00ff00', '', 0, 'Shlomy',   40, 1, 1, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(121, 'Constructor Proc',     '2/21/2008', '3/9/2008',  '00ffff', 'http://www.yahoo.com', 0, 'Brian T.', 60, 0, 12, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(122, 'Task Variables',       '3/6/2008',  '3/11/2008', 'ff0000', 'http://google.com', 0, '',         60, 0, 12, 1,121));
                        //g.AddTaskItem(new JSGantt.TaskItem(123, 'Task Functions',       '3/9/2008',  '3/29/2008', 'ff0000', 'http://google.com', 0, 'Anyone',   60, 0, 12, 1, 0, 'This is another caption'));
                        //g.AddTaskItem(new JSGantt.TaskItem(2,   'Create HTML Shell',    '3/24/2008', '3/25/2008', 'ffff00', 'http://google.com', 0, 'Brian',    20, 0, 0, 1,122));
                        //g.AddTaskItem(new JSGantt.TaskItem(3,   'Code Javascript',      '',          '',          'ff0000', 'http://google.com', 0, 'Brian',     0, 1, 0, 1 ));
                        //g.AddTaskItem(new JSGantt.TaskItem(31,  'Define Variables',     '2/25/2008', '3/17/2008', 'ff00ff', 'http://google.com', 0, 'Brian',    30, 0, 3, 1, ,'Caption 1'));
                        //g.AddTaskItem(new JSGantt.TaskItem(32,  'Calculate Chart Size', '3/15/2008', '3/24/2008', '00ff00', 'http://google.com', 0, 'Shlomy',   40, 0, 3, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(33,  'Draw Taks Items',      '',          '',          '00ff00', 'http://google.com', 0, 'Someone',  40, 1, 3, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(332, 'Task Label Table',     '3/6/2008',  '3/11/2008', '0000ff', 'http://google.com', 0, 'Brian',    60, 0, 33, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(333, 'Task Scrolling Grid',  '3/9/2008',  '3/20/2008', '0000ff', 'http://google.com', 0, 'Brian',    60, 0, 33, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(34,  'Draw Task Bars',       '',          '',          '990000', 'http://google.com', 0, 'Anybody',  60, 1, 3, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(341, 'Loop each Task',       '3/26/2008', '4/11/2008', 'ff0000', 'http://google.com', 0, 'Brian',    60, 0, 34, 1, "332,333"));
                        //g.AddTaskItem(new JSGantt.TaskItem(342, 'Calculate Start/Stop', '4/12/2008', '5/18/2008', 'ff6666', 'http://google.com', 0, 'Brian',    60, 0, 34, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(343, 'Draw Task Div',        '5/13/2008', '5/17/2008', 'ff0000', 'http://google.com', 0, 'Brian',    60, 0, 34, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(344, 'Draw Completion Div',  '5/17/2008', '6/04/2008', 'ff0000', 'http://google.com', 0, 'Brian',    60, 0, 34, 1));
                        //g.AddTaskItem(new JSGantt.TaskItem(35,  'Make Updates',         '10/17/2008','12/04/2008','f600f6', 'http://google.com', 0, 'Brian',    30, 0, 3,  1));

                        g.Draw();	
                        g.DrawDependencies();    
                    </script>
                </div>-->

            </div>
        </div>   
        </div>        
            
    
</div>
