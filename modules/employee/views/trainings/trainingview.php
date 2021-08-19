<?php
$this->title= 'Training Programmes Requests View';


?>

<style>
    .action_div
    {
        width: 100%;
    }

    .select_action_one
    {
        width: 50%;
    }
    

</style>
<hr>
<div class="action_div">
    <div class="select_action_one">
        <?php
            $back_url =Yii::$app->homeUrl."employee/trainings/".$back_url_text."?securekey=$menuid";
        ?>
       <a href="<?php echo $back_url;?>" class="btn btn-success btn-sm sl">Back</a>
    </div>
</div>
<hr>

<?php
    /*echo '<pre>';
    print_r($tpms);
    echo '</pre>';

    die();*/
?>

    <div class="row">
        <div class="col-sm-3">
            <label>Employee Name</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['Name_eng']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Employee Code</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['employee_code']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Department</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['dept_name']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Course Name</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['course_name']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Technology Name</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['technology_name']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Trainer Name</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['trainer_name']?>" readonly="" />
        </div>
        <div class="col-sm-3">
            <label>Start Date</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['start_date']?>" readonly="" />
        </div>

        <div class="col-sm-3">
            <label>End Date</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['end_date']?>" readonly="" />
        </div>

        <div class="col-sm-3">
            <label>Start Time</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['start_time']?>" readonly="" />
        </div>

        <div class="col-sm-3">
            <label>End Time</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['end_time']?>" readonly="" />
        </div>

        <div class="col-sm-3">
            <label>Status</label>
            <input type="text" class="form-control form-control-sm" value="<?=$tpms['status']?>" readonly="" />
        </div>


     
        <?php
            
            $current_employee_code = Yii::$app->user->identity->employee_code;
            $current_employee_role_name = Yii::$app->user->identity->role_name;

            // echo "<pre>"; print_r(Yii::$app->user->identity);
            // die();

            $tmp_employee_code = Yii::$app->utility->encryptString($tpms['employee_code']); 
            $tpm_id = Yii::$app->utility->encryptString($tpms['tpm_id']); 
            $applied_id = Yii::$app->utility->encryptString($tpms['applied_id']); 

            if($current_employee_role_name == 'FLA')
            {
                $approved='Recommended';
                $approvedD = 'Recommend';
            }
            else
            {
                $approved='Approved';
                $approvedD='Approve';
            }
            
            $reject='Reject';

            $url =Yii::$app->homeUrl."employee/trainings/trainingrequestaction?securekey=$menuid&emp_code=$tmp_employee_code&tpm_id=$tpm_id&applied_id=$applied_id&status=$reject";
            $url1 =Yii::$app->homeUrl."employee/trainings/trainingrequestaction?securekey=$menuid&emp_code=$tmp_employee_code&tpm_id=$tpm_id&applied_id=$applied_id&status=$approved";
        ?>

        <!-- <input type="hidden" id="tmp_employee_code" value="<?=$tmp_employee_code?>" /> 
        <input type="hidden" id="tpm_id" value="<?=$tpm_id?>" /> 
        <input type="hidden" id="applied_id" value="<?=$applied_id?>" /> --> 
    </div>
    <br>
    <div class="row">

        <div class="col-sm-6">
        <br>
            <a href="<?php echo $url1;?>" name="appr_t" value="appr_t" class="btn btn-success btn-sm sl"><?=$approvedD?></a>
            <a href="<?php echo $url;?>" name="rej_t" value="rej_t" class="btn btn-danger btn-sm sl">Reject</a>
        </div>
    </div>

    <script type="text/javascript">
        
        /*function actionFun(vald,urlc)
        {
            var tmp_employee_code = $('#tmp_employee_code').val();
            var tpm_id = $('#tpm_id').val();
            var applied_id = $('#applied_id').val();
            var status = vald;

            $.ajax({
                url: urlc,
                type: 'GET',
                dataType: 'html',
                success: function (data) {
                    $('#').html(data);
                }
            });


        }*/
    </script>