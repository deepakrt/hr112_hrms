<?php
$this->title= "Attendance";
use yii\widgets\ActiveForm;
$list=array();
$month = 2;
$year = 2018;

for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);         
    if (date('m', $time)==$month)      
        $list[]=date('Y-m-d-D', $time);
}

//echo "<pre>";
//print_r($attendance);; die;
?>
<style>
    .attndnc-horizontal{
        width: 800px;
        overflow: auto;
        height: 300px;
    }
    .mini_calendar th, .mini_calendar td {
	width: 200px;
	border: 1px solid lightgray;
        text-align: center;
    }
    .cheader{
        height: 50px;
        background: #3F9E89;
        color:#fff;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <?php 
        $url = Yii::$app->homeUrl."employee/information/attendance?securekey=$menuid";
        $form = ActiveForm::begin(['id'=>'calenderForm','action'=>$url, 'method'=>'GET']); ?>
        <div class="row">
            <div class="offset-1 col-sm-5">
                <label>Month</label>
                <select class="form-control form-control-sm viewCalender" name="Calender[month]">
        <!--            <option value="">Select Month</option>-->
                    <?php 
                    for($i=1;$i<=12;$i++){
                        $i = sprintf("%02d", $i);
                        $selected = "";
                        if($curMonth == $i){
                            $selected = "selected='selected'";
                        }
                        $ii = Yii::$app->utility->encryptString($i);
                        echo "<option $selected value='$ii'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-5">
                <label>Year</label>
                <select class="form-control form-control-sm viewCalender" name="Calender[year]">
                    <!--<option value="">Select Year</option>-->
                    <?php 
                    $curYr = date("Y");
                    for($i=2014;$i<=$curYr;$i++){
                        //$i = sprintf("%02d", $i);
                        $selected = "";
                        if($yr == $i){
                            $selected = "selected='selected'";
                        }
                        $ii = Yii::$app->utility->encryptString($i);
                        echo "<option $selected value='$ii'>$i</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
    <div class="col-sm-6">
        <br>
        <button type="button" class="btn btn-success btn-sm" id="view-calender" onclick="changeCalender('C')">Calender View</button>
        <button type="button" class="btn btn-secondary btn-sm" id="view-horizontal" onclick="changeCalender('H')">Horizontal</button>
        <button type="button" class="btn btn-secondary btn-sm" id="view-vertical" onclick="changeCalender('V')">Vertical</button>
    </div>
</div>
<hr>
<div class="attndnc-calender">
    <h6><b><?=$curMonth?> / <?=$yr?></b></h6>
    <?=Yii::$app->hr_utility->generate_calendar($attendance, date('Y', $ccctime), date('n', $ccctime), $days, 1, null, 0);?>
</div>
<div class="attndnc-horizontal" style="display: none;">
    
    <table class="table-bordered horizontalview">
        <tr>
            <th>Month / Day</th>
            <?php 
            for($d=1; $d<=31; $d++){
                $d = sprintf("%02d", $d);
                echo "<th>$d</th>";
            }
            ?>
        </tr>
        <tr>
            <td><?=$curMonth."-".$yr?></td>
            <?php 
            foreach($attendance as $att){
                $status = $att['status'];
                echo "<td>$status</td>";
            }
            ?>
        </tr>
        <tr>
            <td></td>
             <?php 
            foreach($attendance as $att){
                $status = $att['status'];
                echo "<td>$status</td>";
            }
            ?>
        </tr>
    </table>
</div>
<div class="attndnc-vertical" style="display: none;">
    <table class="table table-bordered">
        <tr>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php 
        foreach($attendance as $att){
            $status = $att['status'];
        ?>
        <tr>
            <td><?=date('d-m-Y', strtotime($att['attendancedate']))?></td>
            <td><?=$status?></td>
        </tr>
        <?php
        }
        ?>
        
    </table>
</div>
<script>
$(document).ready(function(){
    $('.viewCalender').change(function(){
        $("#calenderForm").submit();
    });
});
</script>