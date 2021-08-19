<?php
use yii\widgets\ActiveForm;
$this->title= 'Employee Service Details';
?>
<br>
<div class="col-sm-12 text-right" style="margin-bottom: 10px">
<button type="button" class="btn btn-success btn-sm mybtn" data-toggle="modal" data-target=".exampleModalCenter" >Add Services Details</button>
</div>
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
            $viewUrl = Yii::$app->HomeUrl."admin/manageservicedetail/view?securekey=$menuid&securecode=$code";
            echo "<tr>
                <td>$i</td>
                <td>".$allEmp['employee_code']."</td>
                <td>".$allEmp['fullname']."</td>
                <td>".$allEmp['desg_name']."</td>
                <td>".$allEmp['dept_name']."</td>
                <td>".$allEmp['employment_type']."</td>
                <td><a href='$viewUrl' class='linkcolor'>View Service Detail</a></td>
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
<div class="modal fade exampleModalCenter" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDataDivDisp">  
<?php ActiveForm::begin(); ?>
      <div class="row">
        <div class="col-md-4"><b> Employee Code</b></div>
        <div class="col-md-6"><input type="text" name="empcode" required=""></div>  
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6"><button type="submit" class="btn btn-success btn-sm"  >Submit</button></div>
      </div> 
      <?php ActiveForm::end(); ?>    
      </div>
    </div>
  </div>
</div>