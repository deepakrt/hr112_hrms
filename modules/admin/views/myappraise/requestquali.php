<?php
use yii\widgets\ActiveForm;
$this->title= 'Requests for Add Qualification';
$requests = Yii::$app->utility->get_qualification(NULL, NULL, NULL, "Unverified");
//echo "<pre>";print_r($requests);
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Qualification</th>
            <th>Status</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($requests)){$i =1;
            foreach($requests as $q){
                $percentage = $grade ="-";
                if(!empty($q['grade'])){ $grade = $q['grade']; }
                if(!empty($q['percentage'])){ $percentage = $q['percentage']; }
                if(!empty($q['CGPA'])){ $percentage = $q['CGPA']; }
                $doc = Yii::$app->homeUrl.$q['docs'];
                $doc = "<a href='$doc' target='_blank'><img src='$doc' width:100 /></a>";
                $quali = $q['other_quali'];
                if(!empty($q['qualification_level'])){
                    $quali = $q['qualification_level'];
                }
                $key1 = base64_encode("1");
                $key2 = base64_encode("2");
                $eq_id = Yii::$app->utility->encryptString($q['eq_id']);
                $empcode = Yii::$app->utility->encryptString($q['employee_code']);
                $verifyUrl = Yii::$app->homeUrl."admin/myappraise/updatequalifi?securekey=$menuid&eq_id=$eq_id&key=$key1&empcode=$empcode";
                $rejectUrl = Yii::$app->homeUrl."admin/myappraise/updatequalifi?securekey=$menuid&eq_id=$eq_id&key=$key2&empcode=$empcode";
        ?>
        <tr>
            <td>
                <input type="hidden" id="fullname_<?=$i?>" value="<?=$q['fullname']?>" />
                <input type="hidden" id="desg_name_<?=$i?>" value="<?=$q['desg_name']?>" />
                <input type="hidden" id="dept_name_<?=$i?>" value="<?=$q['dept_name']?>" />
                <input type="hidden" id="employee_code_<?=$i?>" value="<?=$q['employee_code']?>" />
                <input type="hidden" id="quali_type_<?=$i?>" value="<?=$q['quali_type']?>" />
                <input type="hidden" id="other_quali_<?=$i?>" value="<?=$q['other_quali']?>" />
                <input type="hidden" id="discipline_<?=$i?>" value="<?=$q['discipline']?>" />
                <input type="hidden" id="Institute_<?=$i?>" value="<?=$q['Institute']?>" />
                <input type="hidden" id="univ_board_<?=$i?>" value="<?=$q['univ_board']?>" />
                <input type="hidden" id="address_<?=$i?>" value="<?=$q['address']?>" />
                <input type="hidden" id="passed_on_<?=$i?>" value="<?=date('d/m/Y', strtotime($q['passed_on']))?>" />
                <input type="hidden" id="grade_<?=$i?>" value="<?=$grade?>" />
                <input type="hidden" id="percentage_<?=$i?>" value="<?=$percentage?>" />
                <input type="hidden" id="CGPA_<?=$i?>" value="<?=$q['CGPA']?>" />
                <input type="hidden" id="doc_type_<?=$i?>" value="<?=ucfirst($q['doc_type'])?>" />
                <input type="hidden" id="doc_<?=$i?>" value="<?=$doc?>" />
                <input type="hidden" id="status_<?=$i?>" value="<?=$q['status']?>" />
                <input type="hidden" id="submiton_<?=$i?>" value="<?=date('d/m/Y', strtotime($q['created_date']))?>" />
                <input type="hidden" id="qualilfi_<?=$i?>" value="<?=$q['qualification_level']?>" />
            <?=$i?></td>
            <td><?=$q['fullname']?>, <?=$q['desg_name']?></td>
            <td><?=$q['dept_name']?></td>
            <td><?=$quali?></td>
            <td><?=$q['status']?></td>
            <td><a href="javascript:void(0)" class="linkcolor" onclick="qualifmodal(<?=$i?>)">Preview</a></td>
            <td>
                <a href="<?=$verifyUrl?>" class="linkcolor">Verify</a> 
                <a href="<?=$rejectUrl?>" class="danger-link">Reject</a>
            </td>
        </tr>	
        <?php $i++;	}
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Qualification</th>
            <th>Status</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<div class="modal fade" id="qualifmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Qualification Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!--<div class="col-sm-12">-->
                        <h6>Employee Detail:-</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td><b>Employee Code : </b><br><span id="ec"></span></td>
                                <td><b>Employee Name : </b><br><span id="en"></span></td>
                                <td><b>Department : </b><br><span id="dpt"></span></td>
                            </tr>
                        </table>
                        <h6>Qualification Detail:-</h6>
                        <table id="acdamik" class="table table-bordered" style="display: none;">
                            <tr>
                                <td><b>Qualification : </b><br><span id="qua"></span></td>
                                <td><b>Discipline : </b><br><span id="dis"></span></td>
                                <td><b>Institute : </b><br><span id="ins"></span></td>
                            </tr>
                            <tr>
                                <td><b>University/ Board : </b><br><span id="uni"></span></td>
                                <td><b>Institute Address : </b><br><span id="add"></span></td>
                                <td><b>Passed on : </b><br><span id="pass"></span></td>
                            </tr>
                            <tr>
                                <td><b>Grade : </b><br><span id="grade"></span></td>
                                <td><b>Percentage : </b><br><span id="per"></span></td>
                                <td><b>C.G.P.A. : </b><br><span id="cgpa"></span></td>
                            </tr>
                            <tr>
                                <td><b>Submitted On : </b><br><span id="subon"></span></td>
                                <td><b>Status : </b><br><span id="sts"></span></td>
                                <td><b>Document Type : </b><br><span id="doc_type"></span></td>                                
                            </tr>
                            <tr>
                                <td colspan="2"><b>Document: </b><br><span id="doc"></span></td>
                                <td></td>
                            </tr>
                        </table>
                        <table id="other_qu" class="table table-bordered" style="display: none;">
                            <tr>
                                <td><b>Qualification : </b><br><span id="otherqua"></span></td>
                                <td><b>Passed on : </b><br><span id="other_pass"></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Grade : </b><br><span id="other_grade"></span></td>
                                <td><b>Percentage : </b><br><span id="other_per"></span></td>
                                <td><b>C.G.P.A. : </b><br><span id="other_cgpa"></span></td>
                            </tr>
                            <tr>
                                <td><b>Submitted On : </b><br><span id="other_subon"></span></td>
                                <td><b>Status : </b><br><span id="other_sts"></span></td>
                                <td><b>Document Type : </b><br><span id="other_doc_type"></span></td>                                
                            </tr>
                            <tr>
                                <td colspan="2"><b>Document: </b><br><span id="other_doc"></span></td>
                                <td></td>
                            </tr>
                        </table>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>