<?php
$this->title="Service Information";
$current = Yii::$app->utility->get_service_details(Yii::$app->user->identity->e_id, "Current");
$servicedetail = Yii::$app->utility->get_service_details(Yii::$app->user->identity->e_id, "Full");

?>

<div class="col-sm-12">
    <br>
    <h6> Appointment Info:</h6>
    <table class="table table-bordered">
        <tr>
            <th>Emp Id</th>
            <th>Emp Name</th>
            <th>Joining Date</th>
            <th>Staff Type</th>
            <th>Scale</th>
            <th>Basic/Pipb/Cons Pay</th>
            <th>Recru. Mode</th>
        </tr>
    <?php 
        if(!empty($current))
        {
//            foreach($current as $current)
//            {
                $empname=Yii::$app->user->identity->fullname;
                $employee_code=Yii::$app->user->identity->employee_code;
                $jnddate=Yii::$app->user->identity->joining_date;
                $jnddate=date('d-M-Y', strtotime($jnddate));
                $employment_type=Yii::$app->hr_utility->fetchstaftype($current['employment_type']);
                $basic_cons_pay= $current['basic_cons_pay'];
                $scale= $current['grade_pay_scale'];
                
                echo "
                    <tr>
                        <td>$employee_code</td>
                        <td>$empname</td>
                        <td>$jnddate</td>
                        <td>$employment_type</td>
                        <td>$scale</td>
                        <td>$basic_cons_pay</td>
                        <td>".RECRU_MODE."</td>
                    </tr>";
//            }
        }
        ?>
    </table>
    <h6> Basic Rate Change Info:</h6>
    <table class="table table-bordered">
        <tr>
            <th>Date of Change</th>
            <th>Financial Eff From</th>
            <th>Staff Type</th>
            <th>Cons. Pay</th>
            <th>HRA</th>
            <th>Total Pay</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
        <?php 
        if(!empty($servicedetail))
        {
            foreach($servicedetail as $servicedetailvalue)
            {
                $date_of_change= $servicedetailvalue['date_of_change'];
                $date_of_change=date('d-M-Y', strtotime($date_of_change));
                $effected_from= $servicedetailvalue['effected_from'];
                $effected_from=date('d-M-Y', strtotime($effected_from));
                $employment_type=Yii::$app->user->identity->employment_type;
                $employment_type=Yii::$app->hr_utility->fetchstaftype($employment_type);
                $basic_cons_pay= $servicedetailvalue['basic_cons_pay'];
                // $hra= $servicedetailvalue['hra'];
				$reason=$status =$total_pay = $hra="";
                //$total_pay= $servicedetailvalue['total_pay'];
                //$status= $servicedetailvalue['status'];
                //$reason= $servicedetailvalue['reason'];
                echo "
                    <tr>
                        <td>$date_of_change</td>
                        <td>$effected_from</td>
                        <td>$employment_type</td>
                        <td>$basic_cons_pay</td>
                        <td>$hra</td>
                        <td>$total_pay</td>
                        <td>$reason</td>
                        <td>$status</td>
                    </tr>";
            }
        }
        ?>
    </table>
</div>
<script>
function leavecarddetail(id)
{
    var url = BASEURL + 'employee/leave/viewleavecard?leavetype=' + id + '';
    window.open(url, '_blank');
}
</script>