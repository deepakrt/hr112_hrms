<?php
$this->title = "Employee Department Mapping";
use yii\widgets\ActiveForm;
?>
<input type="hidden" id='menuid' value='<?=$menuid?>' />
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-4">
        <label>Employee Code</label>
        <input type="text" class="form-control form-control-sm" onkeypress="return allowOnlyNumber(event)" id="emp_code" placeholder="Employee Code" />
    </div>
    <div class="col-sm-4">
        <br>
        <button type="button" class="btn btn-success btn-sm" onclick="getempdept()">Search</button>
    </div>
</div>

<hr class='hrline'>
<div id="empinfo">
    
</div>

<!-- Modal -->
<div class="modal fade" id="addnewdeptmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Assign New Department</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label><span class="hindishow12">विभाग</span> Department</label>
                        <select class="form-control form-control-sm" id='department'>
                            <option value="">Select</option>
                            <?php 
                            $dept = Yii::$app->utility->get_dept(NULL);
                            foreach($dept as $d){
                                echo "<option value='$d[dept_id]'>$d[dept_name]</option>";
                            }
                            ?>
                        </select>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <label><span class="hindishow12">भूमिका</span> Role</label>
                        <select class="form-control form-control-sm" id='roleid'>
                            <option value="">Select</option>
                            <?php 
                            $role= Yii::$app->utility->get_roles(NULL);
                            foreach($role as $d){
                                echo "<option value='$d[role_id]'>$d[role]</option>";
                            }
                            ?>
                        </select>
                        <hr>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button type="button" class="btn btn-success btn-sm" onclick="submitNewDept()">Submit</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>