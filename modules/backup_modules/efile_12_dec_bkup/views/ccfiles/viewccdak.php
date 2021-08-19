<?php
use app\models\EfileMasterCategory;
use app\models\ProjectList;
use app\models\EfileDakDocs;
use yii\widgets\ActiveForm;
use app\models\EfileDakGroups;
use app\models\EfileDakNotes;

$this->title = "View Dak";
use dosamigos\ckeditor\CKEditor;
$noteid = $note_subject = $note_comment = "";

// echo "<pre>";print_r($draft_model);
$filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileinfo['file_id'],NULL);
$filedocspath =  "";
if(!empty($filedocs)){
$filedocspath = Yii::$app->Dakutility->makefilefromdocs($fileinfo['file_id']);
}
$fileidfordnl=Yii::$app->utility->encryptString($fileinfo['file_id']);

$notes = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->asArray()->all();
$notepadding = $noteFont =  $showNotes = "style=''";
$showdoc = "style=''";
$docClass = "col-sm-6 paddingzero";
$noteClass = "col-sm-6 paddingzero";
$rightlogoCSS = $leftlogoCSS = "width:50px;";
$noteTitle_1 = $noteTitle_2 = "";
$padding20 = "";
if(empty($notes)){
    $showNotes = "style='display:none;'";
    if(!empty($filedocs)){
        $docClass = "col-sm-12";
    }
	
}else{
    if(empty($filedocs)){
        $padding20 = "padding:30px;";
        $leftlogoCSS = "width:85%;";
        $rightlogoCSS = "width:12%;";
        $noteTitle_1 = "font-size:32px;";

        $noteTitle_2 = "font-size:20px;font-weight:bold;";
        $noteClass = "col-sm-12";
        $showdoc = "style='display:none;'";
        $noteFont = "style='padding:0px;font-size:14px;'";
    }
}
// echo "**$showNotes";die;
$remarks = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'R', 'is_active'=>'Y'])->asArray()->all();
// echo "<pre>";print_r(Yii::$app->user->identity);die;

$fid = Yii::$app->utility->encryptString($fileinfo['file_id']);
$map_id = Yii::$app->utility->encryptString(Yii::$app->user->identity->map_id);
$noteBG = "";
$showNotes = "Y";

?>
<style>
    #cke_14, #cke_68, #cke_72, #cke_29, #cke_34 { display: none !important;}
    .cke_toolbar{float: none !important}
</style>
<input id='menuid' type='hidden' value='<?=$menuid?>' />
<!--<button type="button" class="btn btn-info" onclick="download_file('<?=$fid?>', '<?=$map_id?>')">Check PDF</button>-->
<div class='row'>
    <div class='col-sm-12'>
        <?php if(!empty($receiptInfo)){ 
        $recNo = $receiptInfo['dak_number']." Dated ".date('d-m-Y', strtotime($receiptInfo['rec_date']));
        $dist = Yii::$app->fts_utility->get_master_districts($receiptInfo['org_district'], NULL);
        $address = $receiptInfo['org_address'];
        if(!empty($dist)){
            $address .= " Distt. $dist[district_name], $dist[state_name]";
        }

        $empinfo = Yii::$app->utility->get_employees($receiptInfo['dak_fwd_to']);
        $fwdto = $empinfo['fullname'].", ".$empinfo['desg_name'];
        ?>
        <h5 class='text-center'><b><span class='hindishow'>रसीद जानकारी</span> / Receipt Details:-</b></h5>
        <ul class='fileinfo'>
            <li><b><span class="hindishow12">रसीद संख्या तथा तारीख </span> / Receipt No. & Date</b><br><?=$recNo?></li>
            <li><b><span class="hindishow12">से प्राप्त किया / </span>Received From </b><br> <?=$receiptInfo['rec_from'].", $address"?></li>
            <li><b><span class="hindishow12">प्राप्त साधन / </span>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
            <?php if(!empty($receiptInfo['dak_summary'])){ ?>
            <li><b><span class="hindishow12">संक्षिप्त सारांश </span>/ Summary</b><br> <?=$receiptInfo['dak_summary']?></li>
            <?php } ?>
            <?php if(!empty($receiptInfo['dak_remarks'])){ ?>
            <li><b><span class="hindishow12">टिप्पणी / </span>Remarks</b><br> <?=$receiptInfo['dak_remarks']?></li>
            <?php } ?>
            <li><b><span class="hindishow12">अग्रेषित दिनांक</span>Forwarded On</b><br> <?=date('d-m-Y', strtotime($receiptInfo['forwarded_date']))?></li>
            <li><b><span class="hindishow12">किस को अग्रेषित</span>Forwarded To</b><br> <?=$fwdto?></li>
        </ul>
        <hr class='hrline'>
        <?php	}
        ?>
        <h5 class='text-center'><b><span style="font-size: 18px;">फ़ाइल विवरण </span>/ File Details:-</b></h5>
        <?php 
        $refNo = $fileinfo['reference_num']." Date ".date('d-m-Y', strtotime($fileinfo['reference_date']));
        $cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();

        $project = ProjectList::find()->where(['project_id' => $fileinfo['file_project_id']])->asArray()->one();

        $refTitle = "<b><span class='hindishow12'>रसीद संख्या तथा दिनांक</span> / Ref. No. & Date</b>";

        if($fileinfo['initiate_type'] == 'P'){
            $refTitle = "<b><span class='hindishow12'>प्रस्ताव शीर्षक तथा दिनांक</span> /Proposal Title & Date</b>";
        }
        $action_type = "";
        $efile_action_type = Yii::$app->fts_utility->efile_get_actions($fileinfo['action_type'], NULL);
        if(!empty($efile_action_type)){
            $action_type = $efile_action_type['action_name'];
        }
        ?>
        
        <ul class='fileinfo'>
            <li><b><span class="hindishow12">स्थिति</span> / Status</b><br> <?=$fileinfo['status']?></li>
            <li><?=$refTitle?><br><?=$refNo?></li>
            <li><b><span class="hindishow12">श्रेणी</span> / Category</b><br> <?=$cat['name']?></li>
            <?php if(!empty($project)){ ?>
            <li><b><span class="hindishow12">परियोजना का नाम</span> / Project Name</b><br> <?=$project['project_name']?></li>
            <?php } ?>
            <li><b><span class="hindishow12">गोपनीय है?</span> / Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
            <li><b><span class="hindishow12">प्राथमिकता</span> / Priority</b><br> <?=$fileinfo['priority']?></li>
            <li><b><span class="hindishow12">उद्देश्य </span> / Forward Purpose</b><br> <?=$action_type?></li>
            <li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
        </ul>

        <p class='text-justify'><b><span class="hindishow12">विषय</span> / Subject:- </b><?=$fileinfo['subject']?></p>
        <hr>
        <?php if(!empty($fileinfo['summary'])) { ?>
        <p class='text-justify'><b><span class="hindishow12">फ़ाइल सारांश</span> / File Summary:- </b><?=$fileinfo['summary']?></p>
        <hr>
        <?php } ?>
        <?php if(!empty($fileinfo['remarks'])) { ?>
        <p class='text-justify'><b><span class="hindishow12">फ़ाइल टिप्पणी</span> / File Remarks:- </b><?=$fileinfo['remarks']?></p>
        <?php } 
        
//        echo "<pre>";print_r($movement);
        ?>
        <hr class='hrline'>
    </div>
</div>
<?php if(!empty($filedocs) OR !empty($notes)){ ?>
<div class='row' id="document_view">
    <div id="note_view" class='<?=$noteClass?>' <?=$showNotes?>>
        <div class='text-left'>
            <a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadgreensheet?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-success btn-xs '>Download Note</a>
        </div>

        <div class='greensheet' style='<?=$padding20?>'>
        <?php 
//        echo "<pre>";print_r($notes); die;
        if(!empty($notes)){
            $deplLogo=Yii::$app->homeUrl."images/cdac.jpeg";
            $swachhbharatabhiyan=Yii::$app->homeUrl."images/swacchbharatlogo.jpeg";
            $voucherHTML = "";
            if(!empty($fileinfo['voucher_number'])){
                $voucherHTML = "<p style='text-align: right;color:red;font-weight: bold;font-size:14px;'>Voucher No. $fileinfo[voucher_number]</p>";
                    } 
            echo '<div class="row">
                    <div class="col-sm-2" style="text-align:left"><img src='."$deplLogo".'  style="'.$leftlogoCSS.'"/></div>
                    <div class="col-sm-10"><p   style="text-align:center;'.$noteTitle_1.' ">प्रगत संगणन विकास केंद्र,मोहाली</p>
                    <p  style="text-align:center;'.$noteTitle_2.'">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING,'. ORGANAZATION_CENTRE. '</p></div>
                    <div class="col-sm-12" style="text-align:right"><img src='."$swachhbharatabhiyan".' style="'.$rightlogoCSS.'" /></div>
            </div>';
            echo "<div class='col-sm-12 text-center'>
                            <p style='".$noteFont."'><u><b>NOTE</b></u></p>
                                $voucherHTML
                    </div>";
            $chairman_emp_code = $committeeName = "";
//            if($movement['fwd_to'] == 'G'){
//                $committeeName1 = Yii::$app->fts_utility->get_committee_name($movement['dak_group_id']);
//                $committeeName = $committeeName1['members'];
//                $chairman_emp_code = $committeeName1['chairman_emp_code'];
//            }
            foreach($notes as $n){ 
                
            $subject = $n['note_subject'];
            $ranid = rand(100,1000);
            $noteby = "";
            $note_align = "";
            if($n['file_fwd_type'] == 'G'){
                $committeeName1 = Yii::$app->fts_utility->get_committee_name($n['group_id']);
                
                $committeeName = $committeeName1['members'];
                $chairman_emp_code = $committeeName1['chairman_emp_code'];
                $noteby = $committeeName;
                $note_align = "text-left";
            }else{
				$role_name = "";
				if(!empty($n['current_role'])){
					if($n['current_role'] == '3' OR $n['current_role'] == '1'){
					}else{
						$role = Yii::$app->utility->get_roles($n['current_role']);
						if(!empty($role)){
							$role_name = "<br>($role[role])";
						}
					}
				}
				
                $note_align = "text-right";
                $noteby = Yii::$app->utility->get_employees($n['added_by']);
                $noteby = $noteby['name_hindi']." / ".$noteby['fullname'].",<br>".$noteby['desg_name_hindi']." / ".$noteby['desg_name']."$role_name";
            }
            
            $notedoc = EfileDakDocs::find()->where(['file_id' => $fileinfo['file_id'], 'noteid'=>$n['noteid']])->asArray()->one();

            ?>

            <div class='row' >
                <?php if(!empty($subject )){ 
                echo "<div class='col-sm-12 text-justify' $noteFont>
                        <b>Subject : $subject</b>
                        <hr>
                </div>";
                } ?>
                <div class='col-sm-12 text-right' <?=$noteFont?>>
                        <u><b><?=date('d-m-Y', strtotime($n['added_date']))?></b></u>
                </div>

                <div class='col-sm-12 text-justify note_html' <?=$noteFont?>>
                    <?="<p class='text-justify'>".$n['note_comment']."</p>";?>
                </div>
                <div class='col-sm-12 <?=$note_align?>' <?=$noteFont?>><b><?=$noteby?></b><hr></div>
            </div>
        <?php }
        }
        ?>
        </div>
    </div>
    <div id="doc_div" class='<?=$docClass?>' <?=$showdoc?>>
        <a href="javascript:void(0)" id="tag_document" style="display: block; position: absolute;top: 50%;left:0%;"><img style="width: 50px;" src="<?=Yii::$app->homeUrl?>images/red_tag.png" /></a>

        <div class='text-right'>
            <button type="button" class="btn btn-danger btn-xs" onclick="download_file('<?=$fid?>', '<?=$map_id?>')">Download Document</button>
            
        </div>
        <h5 class='text-center'><b>Document</b></h5>
        <div class="ifrmeborder">
            <?php  $filedocspath = substr($filedocspath,1); ?>
            <object id='pdf_object'  data="<?=Yii::$app->homeUrl.$filedocspath?>#toolbar=0" type="application/pdf" style="width:100%; height:600px;" toolbar=0></object> 
        </div>

    </div>
    <div class="col-sm-5"></div>
    <div class="col-sm-4">
        <?php 
        $alltags = Yii::$app->fts_utility->efile_get_dak_tags($fileinfo['file_id'], "Active", NULL);
        if(!empty($alltags)){
            echo "<br><button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#viewalltags'>Click to Tag on File</button>";
        }
        ?>
    </div>
    <div class="col-sm-3 text-right">
        <?php 
        if(!empty($remarks)){
            echo "<br><button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#viewallremarks'>Click to view Remarks on File</button>";
        } 
        ?>
    </div>
</div>
<?php }else{
	echo "<div class='alert alert-info text-center'>This is file has only protected file.</div>";
} 
$fileid = Yii::$app->utility->encryptString($fileinfo['file_id']);
$ccid = Yii::$app->utility->encryptString($ccInfo['cc_id']);
$moveUrl = Yii::$app->homeUrl."efile/ccfiles/moveininbox?securekey=$menuid&key=$fileid&key2=$ccid";
?>
<div class="text-center">
    <a href="<?=$moveUrl?>" id="moveinbox" class="btn btn-success btn-sm">Move this file in Inbox</a>
</div>
<hr class="hrline" />
<!--File History -->
<?=Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/history_view.php', ['fileinfo'=>$fileinfo]);?>
<!--File History End-->

<!--Group Information -->
<?=Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/group_information.php', ['fileinfo'=>$fileinfo, 'movement'=>""]);?>
<!--END Group Information -->

<div class="modal fade" id="modal_update_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update File Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="update_key" readonly="" value="<?=Yii::$app->utility->encryptString($fileinfo['file_id'])?>"/>
                        <input type="hidden" id="update_key1" readonly="" value="<?=$menuid?>"/>
                        <label><span class="hindishow12">फ़ाइल श्रेणी</span> / File Category</label>
                        <select id="efiledak-file_category_id" class="form-control form-control-sm">
                            <option value="">Select File Category</option>
                            <?php 
                                $showProject = "";
                                $ProjectDisplay = "display:none;";
                                $category = EfileMasterCategory::find()->where(['is_active' => "Y"])->asArray()->all();
                                $cateHtml ="";
                                if(!empty($category)){
                                    $listt = array();
                                    $i=0;
                                    foreach($category as $c){
                                        $file_category_id = Yii::$app->utility->encryptString($c['file_category_id']);
                                        $name = $c['name']." / ".$c['name_hindi'];
                                        $related_to_project = $c['related_to_project'];
                                        $selected = "";
                                        if($fileinfo['file_category_id'] == $c['file_category_id']){
                                            $selected = "selected='selected'";
                                            if($c['related_to_project'] == 'Y'){
                                                $showProject = "Y";
                                                $ProjectDisplay = "";
                                            }
                                        }
                                        echo "<option value='$file_category_id' $selected data-key='$related_to_project'>$name</option>";
                                    }
                                }
                                ?>
                        </select>
                    </div>
                    <div class="col-sm-12" id='project_list' style='<?=$ProjectDisplay?>'>
                        <label><span class="hindishow12">परियोजना का नाम</span> / Project</label>
                        <select id="efiledak-file_project_id" class="form-control form-control-sm">
                            <option value="">Select Project</option>
                            <?php 
                            if($showProject == 'Y'){
                                $projects = ProjectList::find()->where(['is_active' => 'Y'])->asArray()->all();
                                if(!empty($projects)){
                                    foreach($projects as $p){
                                        $project_id = Yii::$app->utility->encryptString($p['project_id']);
                                        $selected = "";
                                        if($p['project_id'] == $fileinfo['file_project_id']){
                                            $selected = "selected=''";
                                        }
                                        echo "<option value='$project_id'$selected >$p[project_name]</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label><span class="hindishow12">प्राथमिकता</span> / Priority</label>
                        <select id="efiledak-priority" class="form-control form-control-sm">
                            <option value="">Select Priority</option>
                            <?php 
                            $priority = Yii::$app->fts_utility->get_efile_priority("G", "");
                            foreach($priority as $p){
                                $selected = '';
                                $name = Yii::$app->fts_utility->onlyCharacter($p['name']);
                                if($name == $fileinfo['priority']){
                                    $selected = 'selected=""';
                                }
                                echo "<option value='$p[id]' $selected>$p[name]</option>";
                            }
                            ?>
                            
                        </select>
                    </div>
                    <div class="col-sm-12"> 
                        <label><span class="hindishow12">उद्देश्य </span> / Forward Purpose</label>
                        <select id="efiledak-forward_for" class="form-control form-control-sm">
                            <option value="">Select Forward Purpose</option>
                            <?php 
                            $efile_action_type = Yii::$app->fts_utility->efile_get_actions(NULL, "1");
                            foreach($efile_action_type as $p){
                                $action_id = Yii::$app->utility->encryptString($p['action_id']);
                                $selected = '';
                                if($p['action_id'] == $fileinfo['action_type']){
                                    $selected = 'selected=""';
                                }
                                echo "<option value='$action_id' $selected>$p[action_name]</option>";
                            }
                            ?>
                            
                        </select>
                    </div>
                    <div class="col-sm-12 text-center"> 
                        <hr class="hrline">
                        <button type="button" class="btn btn-success btn-sm" onclick="updateFileInfo()">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewallremarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">All Remarks on File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          <div class="modal-body">
                <div class='row'>
                    <?php 
                    if(!empty($remarks)){
                        foreach($remarks as $r){
                                    // die("***");
                            if(!empty($r['note_comment'])){
                            $m = Yii::$app->utility->get_employees($r['added_by']);
                            $m = $m['fullname'].", ".$m['desg_name']." ($m[dept_name])";
                    ?>
                    <div class='col-sm-12'>
                        <p><b>Remarks By <?=$m?></b></p>
                        <p class='text-justify'><?=$r['note_comment']?></p>
                        <p class='text-right'>
                                <b>Dated: <?=date('d-m-Y', strtotime($r['added_date']));?></b>
                        </p>
                        <hr>
                    </div>
                    <?php 
                        }
                    }
                    }
                    ?>
                </div>
          </div>
        </div>
    </div>
</div>


<div class="modal fade" id="viewfullnote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Full Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="show_full_note" class="text-justify"></div>
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" id="new_document_tag_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="tag_key" readonly="" value="<?=Yii::$app->utility->encryptString($fileinfo['file_id'])?>"/>
                    <input type="hidden" id="tag_key1" readonly="" value="<?=$menuid?>"/>
                    <div class="col-sm-12"  style="margin-bottom: 10px;">
                        <label>पृष्ठ संख्या / Page Number</label>
                        <input type="text" onkeypress="return allowOnlyNumber(event)" maxlength="4" class="form-control form-control-sm" id="page_number" placeholder="Page Number" />
                    </div>
                    <div class="col-sm-12" style="margin-bottom: 10px;">
                        <label>टिप्पणी / Comment</label>
                        <textarea class="form-control form-control-sm" id="tag_content" placeholder="Tag Content" rows="3"></textarea>
                    </div>
                    <div class="col-sm-12 text-center"  style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-success btn-sm" onclick="AddNewTag()">Submit</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewalltags" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">All Tags on File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          <div class="modal-body">
                <div class='row'>
                    <div class='col-sm-12'>
                        <table class="table table-bordered">
                    <?php 
                    $alltags = Yii::$app->fts_utility->efile_get_dak_tags($fileinfo['file_id'], "Active", NULL);
                    if(!empty($alltags)){
                        foreach($alltags as $r){
                            if(!empty($r['tag_content'])){
                            $m = Yii::$app->utility->get_employees($r['added_by']);
                            $m = $m['fullname'].",<br>".$m['desg_name'];
                            $fID = Yii::$app->utility->encryptString($fileinfo['file_id']);
                            $tag_id = Yii::$app->utility->encryptString($r['tag_id']);
                            $movement_id = Yii::$app->utility->encryptString($movement['id']);
                            $u = Yii::$app->homeUrl."efile/inbox/removefiletag?securekey=$menuid&key=$fID&key1=$tag_id&key2=$movement_id";
                    ?>
                    
                            <tr>
                                <td style="width: 30%;"><?=$m?></td>
                                <td style="width: 60%;"><span style="color:red;font-weight: bold;">Page No. <?=$r['page_number']?> : </span><?=$r['tag_content']?></td>
                                <td  style="width: 10%;"><a href="<?=$u?>" class=" remove_tag" title="Click to Remove Tag"><img src="<?=Yii::$app->homeUrl?>images/details_close.png" /></a></td>
                            </tr>
                        
                    </div>
                    <?php 
                        }
                    }
                    }
                    ?>
                    </table>
                </div>
          </div>
        </div>
        </div>
    </div>
</div>
<input type="hidden" id='checkfile' />

<div class="modal fade bd-example-modal-lg" id='view_single_document' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"  data-backdrop='static' data-keyboard='false'>
	  <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div id='view_single_document_html'></div>
				<a href='' class='btn btn-danger'>Close</a>
			</div>
	  </div>
</div>

<script>
    
    $(document).ready(function(){
        document.onkeydown = function(e) {
			
            if (e.ctrlKey && 
                (e.keyCode === 67 || 
                 e.keyCode === 86 || 
                 e.keyCode === 85 || 
                 e.keyCode === 117)) {
                return false;
            } else {
                return true;
            }
        };
        $(document).keypress("u",function(e) {
            if(e.ctrlKey){
                return false;
            }else{
                return true;
            }
        }); 
        
        setInterval(function(){
            var checkfile = $("#checkfile").val();
            if(!checkfile){
                var path = "<?=$filedocspath?>";
                unlinkfile(path);
            }
        }, 5000);
    });
</script>