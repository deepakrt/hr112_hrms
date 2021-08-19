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
            <th>IN Time</th>
            <th>OUT Time</th>
            <th>Total Time</th>
            <th>Status</th>              
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($attndn)){ 
            $i=1;
            foreach($attndn as $a){

             
                if($a['status']	 == 'Absent'){
                 $class=' text-danger';   
                }else{
                	$class='';   

                }
                //$emp = Yii::$app->utility->get_employees($a['employee_code']);
//                echo "<pre>";print_r($emp);die;
                ?><tr class="<?php echo $class;?>">
                    <td><?php echo $i;?></td>
                    
                    <td><?php echo $a['employee_code'];?></td>
                    <td><?php echo $a['fname']." ".$a['lname'];?></td>
                    <td><?php echo $a['desg_name'];?></td>
                    <td><?php echo $a['dept_name'];?></td>
                    <td><?php echo $a['attendance_date'];?></td>
                    <td><?php echo date('h:i A',strtotime($a['time_in']));?></td>
                    <td><?php echo date('h:i A',strtotime($a['time_out']));?></td>
                    <td><?php echo $a['total_time'].' hrs';?></td>
                    <td><?php echo $a['status'];?></td>
                    
                    
                 </tr>
                 <?php
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
            <th>IN Time</th>
            <th>OUT Time</th>
            <th>Total Time</th>
            <th>Status</th>                
        </tr>
    </tfoot>
</table>

<script>
    function callAttendance(values){
      
        var menuid = $('#menuid').val();
        var url = BASEURL+"hr/biometricattendance?securekey="+menuid+"&key="+values;
        window.location = url;
        exit();
               
    }
    </script>