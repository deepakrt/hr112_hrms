<?php
use yii\widgets\ActiveForm;
$this->title= 'Requests for Add New Family Member';

$reqts = Yii::$app->utility->get_family_details(null);
//echo "<pre>";print_r($reqts);die;
?>
<style>
    .marginbtm{ margin-bottom: 15px;}
</style>
<br>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Member Name</th>
            <th>Relation</th>
            <th>Is Handicap</th>
            <th>Submitted ON</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($reqts)){
            $i=1;
            foreach($reqts as $emp){
                $ef_id = Yii::$app->utility->encryptString($emp['ef_id']);
                $e_id = Yii::$app->utility->encryptString($emp['employee_code']);
                $editUrl = Yii::$app->homeUrl."admin/myappraise/updatefamilydetails?securekey=$menuid&eid=$e_id&efid=$ef_id";
                $editUrl = "<a href='$editUrl' class='linkcolor'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
                $status = base64_encode("Verified");
                $status1 = base64_encode("Rejected");
                $approveUrl = Yii::$app->homeUrl."admin/myappraise/update?securekey=$menuid&eid=$e_id&status=$status&efid=$ef_id";
                $rejUrl = Yii::$app->homeUrl."admin/myappraise/update?securekey=$menuid&eid=$e_id&status=$status1&efid=$ef_id";
                $document_path = Yii::$app->homeUrl.Employees_Photo_Sign.$emp['document_path'];
                echo "<tr>";
                echo "<td>$i
                    <input type='hidden' id='relation_name_$i' value='".$emp['relation_name']."' readonly />
                    <input type='hidden' id='m_name_$i' value='".$emp['m_name']."' readonly />
                    <input type='hidden' id='marital_status_$i' value='".$emp['marital_status']."' readonly />
                    <input type='hidden' id='m_dob_$i' value='".date('d-M-Y', strtotime($emp['m_dob']))."' readonly />
                    <input type='hidden' id='handicap_$i' value='".$emp['handicap']."' readonly />
                    <input type='hidden' id='handicate_type_$i' value='".$emp['handicate_type']."' readonly />
                    <input type='hidden' id='handicap_percentage_$i' value='".$emp['handicap_percentage']."' readonly />
                    <input type='hidden' id='monthly_income_$i' value='".$emp['monthly_income']."' readonly />
                    <input type='hidden' id='address_$i' value='".$emp['address']."' readonly />
                    <input type='hidden' id='p_address_$i' value='".$emp['p_address']."' readonly />
                    <input type='hidden' id='document_type_$i' value='".$emp['document_type']."' readonly />
                    <input type='hidden' id='document_path_$i' value='$document_path' readonly />
                </td>";
                echo "<td>".$emp['employee_code']."</td>";
                echo "<td>".$emp['fullname']."</td>";
                echo "<td>".$emp['m_name']."</td>";
                echo "<td>".$emp['relation_name']."</td>";
                echo "<td>".$emp['handicap']."</td>";
                echo "<td>".date('d-M-Y', strtotime($emp['created_date']))."</td>";
                echo "<td>$editUrl</td>";
                //echo "<td><a href='javascript:void(0)' class='linkcolor' onclick='viewModelfamily($i)'>View Detail</a></td>";
                //echo "<td><a href='$approveUrl'class='linkcolor'>Approve</a><br>
                //<a href='$rejUrl' class='danger-link'>Reject</a></td>";
                echo "</tr>";
                ?>
    
        <?php
        $i++;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Member Name</th>
            <th>Relation</th>
            <th>Is Handicap</th>
            <th>Submitted ON</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<!--<div class="modal fade" id="viewModelfamily" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Member Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3 marginbtm"><b>Member Name</b></div>
                        <div class="col-sm-3 marginbtm"><span id="mname"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Relation With Employee</b></div>
                        <div class="col-sm-3 marginbtm"><span id="rwe"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Marital Status</b></div>
                        <div class="col-sm-3 marginbtm"><span id="ms"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Date of Birth</b></div>
                        <div class="col-sm-3 marginbtm"><span id="dob"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Date of Birth</b></div>
                        <div class="col-sm-3 marginbtm"><span id="dob"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Is Handicap</b></div>
                        <div class="col-sm-3 marginbtm"><span id="ih"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Handicap Type</b></div>
                        <div class="col-sm-3 marginbtm"><span id="ht"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Handicap Percentage</b></div>
                        <div class="col-sm-3 marginbtm"><span id="hp"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Monthly Income</b></div>
                        <div class="col-sm-3 marginbtm"><span id="mi"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Home Address</b></div>
                        <div class="col-sm-3 marginbtm"><span id="ha"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Postal Address</b></div>
                        <div class="col-sm-3 marginbtm"><span id="pa"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Document Type</b></div>
                        <div class="col-sm-3 marginbtm"><span id="dt"></span></div>
                        <div class="col-sm-3 marginbtm"><b>Document</b></div>
                        <div class="col-sm-3 marginbtm"><span id="doc"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->