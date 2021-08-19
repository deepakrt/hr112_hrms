<?php
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;

$show = "Y";
if(!empty($movement)){
    if($movement['fwd_to'] != 'E'){
        $show = "N";
    }
}
if($show == 'Y'){
    $EfileDakGroups = EfileDakGroups::find()->where(['file_id'=>$fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->all();
    if(!empty($EfileDakGroups)){
//    echo "<pre>";print_r($EfileDakGroups);
    
?>
<hr class="hrline">
<h6><b>समूह / समिति की पिछली चर्चा / Previous Discussion of Group / Committee</b></h6>
<div id="accordion">
    <?php 
    foreach($EfileDakGroups as $grpInfo){
        $gmem = Yii::$app->utility->get_employees($grpInfo['created_by']);
        $gmem = "$gmem[fullname], $gmem[desg_name] ";
        $grpDt = date('d-M, Y', strtotime($grpInfo['created_date']));
        $group_name = "Group / Committee Name : $grpInfo[group_name]<br>Created By : $gmem on $grpDt";
        $randNum = rand(1000, 100000);
        $heading_id = "heading_$randNum";
        $group_id = "group_$randNum";
        
        $memberInfo = Yii::$app->fts_utility->get_committee_name($grpInfo['dak_group_id']);
        if(!empty($memberInfo)){
    ?>
    <div class="card">
        <div class="card-header" id="<?=$heading_id?>">
            <h5 class="mb-0">
              <button class="btn btn-secondary accordion_sm" data-toggle="collapse" data-target="#<?=$group_id?>" aria-expanded="true" aria-controls="<?=$group_id?>"><?=$group_name?></button>
            </h5>
        </div>
        <div id="<?=$group_id?>" class="collapse" aria-labelledby="<?=$heading_id?>" data-parent="#accordion">
            <div class="card-body">
                <?php 
                echo $memberInfo['table_html'];
                $allremartks = EfileDakGroupMembersRemarks::find()->where(['dak_group_id' =>$grpInfo['dak_group_id'], 'file_id'=>$fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->all();
                if(!empty($allremartks)){ 
                    $i=1;
                    echo "<table class='table table-bordered'>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Member Name</th>
                            </tr>
                        ";
                    foreach($allremartks as $m){
                        
                        $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
                        $role = "Member";
                        if($m['group_role'] == 'CH'){
                                $role = "Chairman";
                        }elseif($m['group_role'] == 'C'){
                                $role = "Convenor";
                        }
                        $Group_Role = "";
                        $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($role)";
                        $date = date('d-m-Y H:i:s', strtotime($m['created_date']));
                        echo "
                                <tr>
                                        <td>$i</td>
                                        <td class='text-justify'><u><b>$memberInfo inputs dated $date</b></u><br>$m[remarks]</td>
                                </tr>
                        ";
                        $i++;
                        $memberInfo = "";
                    }
                    echo "</table>";
                }
                ?>
                
            </div>
        </div>
    </div>
    <?php 
    
            }
        }
    ?>
</div>
<?php 
    }
}
?>



