<?php
use yii\widgets\ActiveForm;
$this->title = "View Leave Application";
?>

<input type='hidden' id='menuid' value='<?=$menuid?>' />

<table class="table table-bordered">
    <tr>
        <td><b>Employee Name</b> : <?=$emp['fullname']?></td>
        <td><b>Designation</b> : <?=$emp['desg_name']?></td>
        <td><b>Department </b>: <?=$emp['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Employee Type</b> : <?=$emp['employment_type']?></td>
        <td><b>Leave From & Till Date</b> : <?=date('d-m-Y', strtotime($app['leave_from']))?> to <?=date('d-m-Y', strtotime($app['leave_to']))?></td>
    </tr>
</table>
<h6><b>Leave Details</b></h6>
<?php ActiveForm::begin(['id' => 'leave_arrove_form','action'=>Yii::$app->homeUrl."hr/approveleaveapplication/updateapplication?securekey=$menuid"]);?>
<table class="table table-bordered">
    <tr>
        <th>Leave Type</th>
        <th>From Date</th>
        <th>From To</th>
        <th>Total Days</th>
        <th>Reason</th>
        <th>Contact No.</th>
    </tr>
    <?php
        //    echo "<pre>";print_r($details);
        $i=0;
        foreach($details as $d){
    ?>
    <input type='hidden' name='Leaves[<?=$i?>][key1]' value='<?=Yii::$app->utility->encryptString($d['leave_type'])?>' />
    <input type='hidden' name='Leaves[<?=$i?>][key2]' value='<?=Yii::$app->utility->encryptString($d['totaldays'])?>' />
    <tr>
        <td><?=$d['desc']?></td>
        <td><?=date('d-m-Y', strtotime($d['req_from_date']))?></td>
        <td><?=date('d-m-Y', strtotime($d['req_to_date']))?></td>
        <td><?=$d['totaldays']?></td>
        <td><?=$d['leave_reason']?></td>
        <td><?=$d['contact_no']?></td>
    </tr>
    <?php $i++; }
    ?>
</table>
<hr>

<input type='hidden' name='App[leave_app_id]' value='<?=Yii::$app->utility->encryptString($app['leave_app_id'])?>' />
<input type='hidden' name='App[employee_code]' value='<?=Yii::$app->utility->encryptString($app['employee_code'])?>' />
<div class="row">
    <div class="col-sm-12">
        <label>Remarks (If any)</label>
        <input type='text' class="form-control form-control-sm" name='App[remarks]' placeholder="Remarks (If any)"  />
    </div>
    <div class="col-sm-3">
        <label>Select Action</label>
        <select class="form-control form-control-sm" name='App[status]' id="action_drop" required="required" onchange="changeStatusAction(this)">
            <?php 
            if(Yii::$app->user->identity->role == '5'){
                $status = Yii::$app->utility->encryptString("Approved");
            }else{
                $status = Yii::$app->utility->encryptString("ABRA");
            }
            ?>
            <option value=''>Select Action</option>
            <option data='Approve' value='<?=$status?>'>Approve</option>
            <option data='Reject' value='<?=Yii::$app->utility->encryptString("Rejected")?>'>Reject</option>
            <option data="Forward" value='<?=Yii::$app->utility->encryptString("Forward")?>'>Forward</option>
        </select>
        <span id="act_error" style="display: none;">Please Select Action.</span>
    </div>
    
    <div class="col-sm-9" id="for_forward_leave">        
    </div>
    <div class="col-sm-3">
        <br>
        <input type='submit' class="btn btn-success btn-sm" value="Submit"/>
        <a href='<?=Yii::$app->homeUrl?>hr/approveleaveapplication?securekey=<?=$menuid?>' class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end();?>


<script type="text/javascript">
   
    $('#leave_arrove_form').submit(function(e)
    {
        /*alert("Submitted");
        e.preventDefault();*/

        $('#act_error').hide();
        $('#dpts_error').hide();
        $('#dsg_error').hide();
        $('#emp_error').hide();

        var selectedVal = $('#action_drop').find(':selected').attr('data');
        var menuid = $('#menuid').val();

        if(selectedVal == 'Forward')
        {
            var dept_id = $('#dept_id').val();            
            var desg_id = $('#desg_id').val();            
            var employee_Data = $('#employee_Data').val();      
            
            
            var errorcnt = 0;
            if(dept_id == -1)
            {
                errorcnt = 1;
                $('#dpts_error').show();
                $('#dpts_error').css('color','Red');
            }
            if(desg_id == -1)
            {
                errorcnt = 1;
                $('#dsg_error').show();
                $('#dsg_error').css('color','Red');
            }
            if(employee_Data == -1 || employee_Data == '')
            {
                errorcnt = 1;
                $('#emp_error').show();
                $('#emp_error').css('color','Red');
            }
                
            if(errorcnt == 1)
            {
                return false;
            }

        }
        else
        {
            if(selectedVal == '')
            {
                $('#act_error').show();
                $('#act_error').css('color','Red');
                return false;
            }
        }

    });

    function changeStatusAction(e)
    {
        $('#for_forward_leave').html('');

        var selectedVal = $(e).find(':selected').attr('data');
        var menuid = $('#menuid').val();

        if(selectedVal == 'Forward')
        {
            $('#for_forward_leave').css({"display": "block"});

            var url = BASEURL+"hr/approveleaveapplication/get_comman_section?securekey="+menuid;
     
            // console.log(attendate+"=====employment_type="+employment_type+"======"+dept_id);

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'JSON',
                data:{ keyval:selectedVal },
                success: function(data){

                    // console.log(data.render_data);


                    $('#for_forward_leave').html(data.render_data);

                    // stopLoader();
                }
            });
        }
        else
        {
            $('#for_forward_leave').css({"display": "none"});
            // $('#for_forward_leave').css({"background-color": "yellow", "font-size": "200%"});
        }
    }

</script>