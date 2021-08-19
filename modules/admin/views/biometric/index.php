<?php
$this->title="View Biometric Attandence";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($attndn);die;
?>
<?php ActiveForm::begin(); ?>
<div class="row">
    <div class="col-6">
        <div class="row">
            <div class="col-10">
                <label>Filter Month Wise</label>
                <input type="month" onchange="callAttendance(this.value)" default="<?php echo $year_month;?>" id="month_wise" value="<?php echo $year_month;?>"   name="month_wise">
        
            </div>
   
        </div>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-10">
                <label>Filter Date Wise</label>
                <input type="date" onchange="callAttendance(this.value)" default="<?php echo $year_month;?>" id="month_wise" value="<?php echo $year_month;?>"   name="month_wise">
        
            </div>
   
        </div>
    </div>
    <input type="hidden" id="menuid" value="<?=$menuid?>" name="menuid">
</div>
<?php ActiveForm::end(); ?>
<h6><b><?=$st?></b></h6>
<hr>

<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Dept</th>
            <th>Attn. Date</th>
            <th>Time of Punch</th>
            <th>Punch Counter</th>              
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($attndn)){ 
            $i=1;
            foreach($attndn as $a){

                // employee_code  punch_time date fname  dept_name  desg_name
                //echo $a;
                /*if($a['attendance_mark'] == 'P'){
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
                $emp = Yii::$app->utility->get_employees($a['employee_code']);*/
//                echo "<pre>";print_r($emp);die;
                echo "<tr>
                    <td>$i</td>
                    
                    <td>".$a['employee_code']."</td>
                    <td>".$a['fname']." ".$a['lname']."</td>
                    <td>".$a['desg_name']."</td>
                    <td>".$a['dept_name']."</td>
                    <td>".$a['attendance_date']."</td>
                    <td>".date('h:i A',strtotime($a['punch_time']))."</td>
                    <td>".$a['punch_counter']."</td>
                    
                    
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
            <th>Punch Counter</th>              
        </tr>
    </tfoot>
</table>


<script>
    function callAttendance(values){
      
        var menuid = $('#menuid').val();
        var url = BASEURL+"admin/biometric?securekey="+menuid+"&key="+values;
        window.location = url;
        exit();
               
    }
    </script>