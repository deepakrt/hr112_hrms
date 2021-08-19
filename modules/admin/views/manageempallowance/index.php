<?php
$this->title = "Employee Children Education Allowance";
//echo "<pre>";print_r(Yii::$app->user->identity);
//$allowsDetails = Yii::$app->utility->get_emp_allowance($param_designation_id, $param_emp_type, $param_financial_yr);
?>
<div class="col-sm-12 text-right">
    <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/manageempallowance/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add Employee Allowance</a>
</div>

<div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
	<thead>
            <tr>
                <th>Sr.</th>
                <th>Financial Year</th>
                <th>Designation</th>
                <th>Emp. Type</th>
                <th>Allowance Type</th>
                <th>Amount (in Rs.)</th>
                <th>Sanc. Type</th>
                <th></th>
            </tr>
	</thead>
	<tbody>
            <?php 
            if(!empty($allowances)){
                $i=1;
                foreach($allowances as $allow){
                    $id = Yii::$app->utility->encryptString($allow['id']);
                    $desg_id = Yii::$app->utility->encryptString($allow['designation_id']);
                    $delUrl = Yii::$app->homeUrl."admin/manageempallowance/deleteallowance?securekey=$menuid&id=$id&desg_id=$desg_id";
                    $delUrl = "<a href='$delUrl' class='linkcolor deleteallow1'>Delete</a>";
                    $emp_type = Yii::$app->hr_utility->fetchstaftype($allow['emp_type']);
                    $allow_type="";
                    $Emp_Allowances = Emp_Allowances;
                    foreach($Emp_Allowances as $aa){
                        if($aa['shortname']==$allow['allowance_type']){
                            $allow_type=$aa['name'];
                        }
                    }
                    ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$allow['financial_yr']?></td>
                <td><?=$allow['desg_name']?></td>
                <td><?=$emp_type?></td>
                <td><?=$allow_type?></td>
                <td><?=$allow['amount']?></td>
                <td><?=$allow['sanc_type']?></td>
                <td><?=$delUrl?></td>
            </tr>
            <?php
             $i++;   }
            }
            ?>
	</tbody>
	<tfoot>
	    <tr>
                <th>Sr.</th>
                <th>Financial Year</th>
                <th>Designation</th>
                <th>Emp. Type</th>
                <th>Allowance Type</th>
                <th>Amount (in Rs.)</th>
                <th>Sanc. Type</th>
                <th></th>
            </tr>
	</tfoot>
    </table>
</div>