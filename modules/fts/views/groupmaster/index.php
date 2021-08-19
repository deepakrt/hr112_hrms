<?php
$this->title = 'Groups Master';
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin();?> 
<input type="hidden" name="Group[group_id]" id="group_id" readonly="" />
<div class="row">
    <div class="col-sm-12">
        <h6><b><u>Add New Group</u></b></h6>
    </div>
    <div class="col-sm-3">
        <label>Group Name</label>
        <input type="text" name="Group[group_name]" id="group_name" class="form-control form-control-sm" required="" placeholder="Group Name" />
    </div>
    <div class="col-sm-4">
        <label>Group Description</label>
        <input type="text" name="Group[group_description]" id="group_description" class="form-control form-control-sm" placeholder="Group Description" required="" />
    </div>
    <div class="col-sm-3">
        <label>Is hierarchical?</label>
        <select name="Group[is_hierarchical]" id="is_hierarchical" class="form-control form-control-sm" required="">
            <option value=''>Select Hierarchical</option>
            <option value='Y'>Yes</option>
            <option value='N'>No</option>
        </select>
    </div>
    <div class="col-sm-2">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit" />
    </div>
</div>
<?php ActiveForm::end();?> 
<hr>
<div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Group Name</th>
                <th>Description</th>
                <th>Is Hierarchical?</th>
                <th></th>
                <th></th>
                <th>Created On</th>
                <th>Last Updated</th>
                <th>Is Active</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php 
//            echo "<pre>";print_r($allGroups);
            if(!empty($allGroups)){
                $i=1;
                foreach($allGroups as $g){
                    
                    
                    $group_id = Yii::$app->utility->encryptString($g['group_id']);
                    $last_modified_date = "-";
                    if(!empty($g['last_modified_date'])){
                        $last_modified_date = date('d-m-Y H:i:s', strtotime($g['last_modified_date']));
                    }
                    $isactive = "Yes";
                    if($g['is_active'] == 'N'){
                        $isactive = "No";
                    }
                    $isharay = "<p style='color:#3F9E89;font-weight: bold'>Yes</p>";
                    $addUrl = $view = "-";
                    if($g['is_hierarchical'] == 'N'){
                        $isharay = "No";
                        
                        $addUrl = Yii::$app->homeUrl."fts/groupmaster/groupmembers?securekey=$menuid&group_id=$group_id";
                        $addUrl = "<a href='$addUrl' class='linkcolor'>Add Member</a>";
                        $vewMem = Yii::$app->fts_utility->fts_get_group_members($g['group_id']);
                        if(!empty($vewMem)){
                            $view ="<a href='javascript:void(0)' data-id='$i' data-key='$group_id' class='viewgroupmembers' style='color:#3F9E89;font-weight: bold'>View Members<a/>";
                        }
                        
                        
                    }elseif($g['is_hierarchical'] == 'Y'){
                        $proess = Yii::$app->fts_utility->fts_get_group_process($g['group_id']);
                        $addUrl1 = Yii::$app->homeUrl."fts/groupmaster/groupprocess?securekey=$menuid&group_id=$group_id";
                        $addUrl = "<a href='$addUrl1' style='color:#3F9E89;font-weight: bold;'>Add Process</a>";
                        if(!empty($proess)){
                            $addUrl = "<a href='$addUrl1' style='color:#3F9E89;font-weight: bold;'>Edit Process</a>";
                            $view ="<a data-id='$i' data-key='$group_id' class='viewgroupprocess' href='javascript:void(0)' style='color:#3F9E89;font-weight: bold'>View Process<a/>";
                        }
                    }
                    $editUrl = "<a href='javascript:void(0)' data-key='$i' class='edit_group' ><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
                ?>
            <tr>
                <td><?=$i?>
                    <input type="hidden" id="group_id_<?=$i?>" value="<?=$group_id?>" readonly="" />
                    <input type="hidden" id="is_hierarchical_<?=$i?>" value="<?=$g['is_hierarchical']?>" readonly="" />
                </td>
                <td id="group_name_<?=$i?>"><?=$g['group_name']?></td>
                <td id="group_desc_<?=$i?>"><?=$g['group_description']?></td>
                <td ><?=$isharay?></td>
                <td><?=$addUrl?></td>
                <td><?=$view?></td>
                <td><?=date('d-m-Y H:i:s', strtotime($g['creation_date']))?></td>
                <td><?=$last_modified_date?></td>
                <td id="is_active_<?=$i?>"><?=$isactive?></td>
                <td><?=$editUrl?></td>
            </tr>   
            <?php $i++;   }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Sr.</th>
                <th>Group Name</th>
                <th>Description</th>
                <th>Is Hierarchical?</th>
                <th></th>
                <th></th>
                <th>Created On</th>
                <th>Last Updated</th>
                <th>Is Active</th>
                <th>Edit</th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Group Members-->
<div class="modal fade" id="groupmemberslist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><span id='viewmemtitle'></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id='table_viewmember' class="table table-bordered">
                            
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Group Process-->
<div class="modal fade" id="groupprocesslist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><span id='viewprocesstitle'></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id='table_viewprocess' class="table table-bordered">
                            
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>