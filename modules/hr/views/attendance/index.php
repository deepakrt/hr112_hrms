<?php
$this->title="View Attandence";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($attndn);
?>
<?php ActiveForm::begin(); ?>
<!--<div class="row">
    <div class="col-sm-3">
        
        <input type="text" class="form-control form-control-sm" placeholder="Select Date" id="search_attn_date" />
    </div>
</div>-->
<?php ActiveForm::end(); ?>
<hr>
<h6><b><?=$st?></b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Dept</th>
            <th>Attn. Date</th>
            <th>Attendance</th>
            <th></th>              
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($attndn)){ 
            $i=1;
            foreach($attndn as $a){
                if($a['attendance_mark'] == 'P'){
                    $att ="Present";
                }elseif($a['attendance_mark'] == 'A'){
                    $att ="Absent";
                }elseif($a['attendance_mark'] == 'L'){
                    $att ="On Leave";
                }elseif($a['attendance_mark'] == 'FHL'){
                    $att ="First Half Leave";
                }elseif($a['attendance_mark'] == 'SHL'){
                    $att ="Second Half Leave";
                }
                $emp = Yii::$app->utility->get_employees($a['employee_code']);
//                echo "<pre>";print_r($emp);die;
                echo "<tr>
                    <td>$i</td>
                    <td>".$emp['employee_code']."</td>
                    <td>".$emp['fullname']."</td>
                    <td>".$emp['desg_name']."</td>
                    <td>".$emp['dept_name']."</td>
                    <td>".date('d-M-Y', strtotime($a['attendance_date']))."</td>
                    <td>$att</td>
                    <td></td>
                 </tr>";
                $i++;
            }
        ?>
            
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Dept</th>
            <th>Attn. Date</th>
            <th>Attendance</th>
            <th></th>              
        </tr>
    </tfoot>
</table>


