<?php
$this->title= 'View Assigned Leaves';
?>
<input type="hidden" value="<?=$menuid?>" id="menuid" readonly="" />
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Emp. Type</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
//    echo "<pre>";print_r($allEmps);
    if(!empty($allEmps)){
        $i=1;
        foreach($allEmps as $allEmp){
            $code = Yii::$app->utility->encryptString($allEmp['employee_code']);
//            $viewUrl = Yii::$app->HomeUrl."admin/manageservicedetail/view?securekey=$menuid&securecode=$code";
            echo "<tr>
                <td>$i</td>
                <td id='code_$i'><input type='hidden' id='empcode_$i' value='$code' /> ".$allEmp['employee_code']."</td>
                <td id='fullname_$i'>".$allEmp['fullname']."</td>
                <td id='desg_name_$i'>".$allEmp['desg_name']."</td>
                <td>".$allEmp['dept_name']."</td>
                <td>".$allEmp['dept_name']."</td>
                <td><a href='javascript:void(0)' data-srno='$i' class='linkcolor viewleavedetail' >View Details</a></td>
                </tr>";
            $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Emp. Type</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<!-- Modal -->
<div class="modal fade" id="viewleavedetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Employee Leave Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td><b>Employee Code: </b> <br><span id="employee_code"></span> </td>
                        <td><b>Employee Name: </b> <br><span id="employee_name"></span> </td>
                        <td><b>Designation: </b> <br><span id="designation"></span> </td>
                    </tr>
                </table>
                <hr>
                <h6>Leaves Details:-</h6>
                <table id="leaveinfo" class="table table-bordered table-hover"></table>
            </div>
        </div>
    </div>
</div>