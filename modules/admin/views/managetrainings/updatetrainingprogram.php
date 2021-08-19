<?php
$this->title = "Update Technology";
use yii\widgets\ActiveForm;
$url =Yii::$app->homeUrl."admin/managetrainings/updatetrainingprogram?securekey=$menuid";
?>
<style>
    .col-sm-3{margin-bottom: 15px;}
</style>

<hr>

<?php $form = ActiveForm::begin(['options'=>['id'=>'updatetrainingprogram']]); ?>
<?php 



foreach($tpms as $tpm){


    ?>
<input type="hidden" id='menuid' value='<?=$menuid?>' />
<div class="row">

    


    <div class="col-sm-3">
        <label>Course Name</label>
         <select  name="Tpm[course_id]" class="form-control form-control-sm">
            <option value="<?php echo $tpm['course_id'];?>" selected="selected"><?php echo $tpm['course_name'];?></option>
            <?php foreach($courses as $course){?>
                <option value="<?php echo $course['course_id'];?>"><?php echo $course['course_name'];?></option>
            <?php }?>
         </select>

         
    </div>
    
    <div class="col-sm-3">
        <label>Technology Name</label>
         <select  name="Tpm[technology_id]" class="form-control form-control-sm">
            <option value="<?php echo $tpm['technology_id'];?>" selected="selected"><?php echo $tpm['technology_name'];?></option>
            <?php foreach($technologies as $technology){?>
                <option value="<?php echo $technology['technology_id'];?>"><?php echo $technology['technology_name'];?></option>
            <?php }?>
         </select>
    
    </div>
    <div class="col-sm-3">
        <label>Course Code</label>
         <input type="text"  name="Tpm[course_code]" value="<?php echo $tpm['Programme_code'];?>" class="form-control form-control-sm " placeholder="Course Code">
           
    
    </div>

    <div class="col-sm-3">
        <label>User Role</label>
         <select  name="Tpm[role_id]" class="form-control form-control-sm">
            
            <?php foreach($roles as $role){?>
                <option value="<?php echo $role['role_id'];?>" <?php if($role['role_id']==$tpm['role_id']){?> selected="selected"<?php }?>><?php echo $role['role'];?></option>
            <?php }?>
         </select>
    
    </div>


    

   
    <div class="col-sm-3">
     <label for="startDate">Start Date</label>
            
          <div class="input-group-addon"><i class="fa fa-table fa-fw"></i></div>
          <input type="date" class="form-control form-control-sm" value="<?php echo $tpm['start_date'];?>" name="Tpm[startDate]" id="startDate">
        


    </div>
    <div class="col-sm-3">
     <label for="startDate">End Date</label>
            
          <div class="input-group-addon"><i class="fa fa-table fa-fw"></i></div>
          <input type="date" class="form-control form-control-sm" value="<?php echo $tpm['end_date'];?>"  name="Tpm[endDate]" id="endDate">
        


    </div>
    <div class="col-sm-3">

          <label for="startTime">Start Time</label>
        
          <div class="input-group-addon"><i class="fa fa-table fa-fw"></i></div>
          <input type="time" class="form-control form-control-sm" value="<?php echo $tpm['start_time'];?>"  name="Tpm[startTime]" id="startTime" >
        
    </div>
    
    <div class="col-sm-3">

          <label for="startTime">End Time</label>
        
          <div class="input-group-addon"><i class="fa fa-table fa-fw"></i></div>
          <input type="time" class="form-control form-control-sm" value="<?php echo $tpm['end_time'];?>"  name="Tpm[endTime]" id="startTime" >
        
    </div>

    <div class="col-sm-3">
        <label>Training Fees</label>
         <input type="text"  name="Tpm[training_fees]" class="form-control form-control-sm" value="<?php echo $tpm['fees'];?>"  placeholder="Training Fees">
           
    
    </div>
    <div class="col-sm-3">
        <label>Installment</label>
         <select class="form-control form-control-sm" name="Tpm[installment]" id="installment" >
              
              <option value="no" <?php if($tpm['installment']=='no'){?> selected="selected" <?php }?>>No</option>
              <option value="yes"<?php if($tpm['installment']=='yes'){?> selected="selected" <?php }?>>Yes</option>
            </select>
    
    </div>
     <div class="col-sm-3">
        <label>Trainer Name</label>
         <select  name="Tpm[trainer_id]" class="form-control form-control-sm">
           
            <?php foreach($trainers as $trainer){?>
                <option value="<?php echo $trainer['trainer_id'];?>" <?php if($tpm['trainer_name']==$trainer['trainer_name']){?> selected="selected" <?php }?>><?php echo $trainer['trainer_name'];?></option>
            <?php }?>
         </select>
    
    </div>
    <div class="col-sm-3">
        <label>Trainer's Amount</label>
         <input type="text"  name="Tpm[trainer_amt]" value="<?php echo $tpm['trainer_amt'];?>" class="form-control form-control-sm" placeholder="Trainer's Amount">
           
    
    </div>
    <div class="col-sm-3">
        <label>Department Name</label>
<?php //echo '<pre>'; print_r($departments); die; ?>
         <select  name="Tpm[department_id]" class="form-control form-control-sm">
            
            <?php foreach($departments as $department){?>
                <option value="<?php echo $department['dept_id'];?>" <?php if($tpm['department_id']==$department['dept_id']){?> selected="selected" <?php }?>><?php echo $department['dept_name'];?></option>
            <?php }?>
         </select>
    
    </div>
    
    <div class="col-sm-3">
        <label>Total Applications limit</label>
         <input type="text"  name="Tpm[seats]" value="<?php echo $tpm['no_of_seats'];?>" class="form-control form-control-sm" placeholder="Total Seats">
           
    
    </div>
    <div class="col-sm-12">
        
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-sm sl" id="addtechnologySubmit">Submit</button>
        </div>
    </div>
</div>
<?php } ?>
<?php ActiveForm::end(); ?>
