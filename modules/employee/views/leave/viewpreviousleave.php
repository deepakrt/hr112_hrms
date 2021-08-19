<?php
$leaves =  Yii::$app->hr_utility->hr_get_leaves('A', Yii::$app->user->identity->e_id, NULL, 'ABRA,Submitted,In-Process,Approved,Rejected');
$info=Yii::$app->user->identity;
//echo "<prE>";print_r($leaves); die;

$this->title="Previous Leave Details";
?>
<input type='hidden' id='menuid' value="<?=$menuid?>" />
<hr>
<div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>App. Date</th>
                <th>From Date</th>
                <th>Till Date</th>
                <th>Leave Type</th>
                <th>Status</th>
                
            </tr>
        </thead>
            <tbody>
                <?php 
                if(!empty($leaves)){
                    $i =1;
                    foreach($leaves as $l)
                    { 
                        $applied_date=date('d-M-Y', strtotime($l['applied_date']));
                        $fromdate=date('d-M-Y', strtotime($l['leave_from']));
                        $leave_to=date('d-M-Y', strtotime($l['leave_to']));
                        $status=$l['status'];
                        $leave_app_id = Yii::$app->utility->encryptString($l['leave_app_id']);
                        $ec = Yii::$app->utility->encryptString($l['employee_code']);
                        $desc = $l['desc'];

                        if($status == 'ABRA')
                        {
                            $status = 'Approved';
                        }

                    ?>
                    <tr>
                        <td><?=$applied_date?></td>
                        <td><?=$fromdate;?></td>
                        <td><?=$leave_to;?></td>
                        <td><?=$desc;?></td>
                        <!-- <td>
                            <a href="javascript:void(0)" class='viewleavedetails1' data-key='<?=$leave_app_id?>' data-key1='<?=$ec?>' ><img src="<?=Yii::$app->homeUrl?>images/view.png" style="width: 23px;"/></a>
                    </td> -->
                    <td><?=$status;?></td>
                    </tr>	
                    <?php $i++;	
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>App. Date</th>
                    <th>From Date</th>
                    <th>Till Date</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </tfoot>
    </table>
</div>

<div class="modal fade" id="leavedata" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:850px;">
    <div class="modal-content">
        <div class="modal-header" style="background-color:#1DABB8">
        <h5 class="modal-title" id="myModalLabel" style="color: white;">Leave Application Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 1; color: white;"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id ="modal_contentdata"></div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('.viewleavedetails1').click(function(){
            $("#modal_contentdata").html('');
            var leaveappid = $(this).attr('data-key');
            var menuid = $('#menuid').val();
            if(leaveappid && menuid){
                var url = BASEURL+"hr/viewapprovedleave/viewleaverequests?securekey="+menuid+"&key="+leaveappid;
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if(data){
                            var ht = $.parseJSON(data);
                            var status = ht.Status;
                            var res = ht.Res;
                            if(status == 'SS'){
                                $("#modal_contentdata").html(res);
                                $("#leavedata").modal();
                                return false;
                            }else{
                                showError(res); 
                                return false;
                            }
                        }else{
                            return false;
                        }
                    }
                });
            }
        });
    })
</script>