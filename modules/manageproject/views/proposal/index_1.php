<?php 
use yii\widgets\ActiveForm;

$this->title = "Proposal List";

?>
<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
<div class="row">
    <div class="col-sm-6 text-left"><h5><b>List of All Proposals</b></h5></div>
    <div class="col-sm-3">
        <form action="<?=Yii::$app->homeUrl?>ordermaster/index?securekey=<?=$menuid?>" method="POST" id="proposal">
            
            <select class="form-control form-control-sm text-center" name="proposal"  style="width: 67%;margin: 5px 0 5px 42px;">                
                <option selected=selected value='1'>All</option>
                <option  value='1'>Active</option>
                <option value="0">Inactive</option>
            </select>
            
        </form>
    </div>
    <div class="col-sm-3 text-right">
        <a href="<?=Yii::$app->homeUrl?>manageproject/proposal/create?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn" title="Add New Proposal">Add New Proposal</a>
    </div>
     <div class="col-sm-12">
        <hr>
        <table id="dataTableShow2" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Client</th>                    
                    <th>Submission Date</th>
                    <th>Validity (in Days)</th>
                    <th>Project Cost</th>                    
                    <th>Expiry Date</th>
                    <th></th>                    
                </tr>
            </thead>
                <tbody>
                    <?php 
                    
//                    echo "<pre>";print_r($projects);
                    $lists='';
                    if(!empty($projects)){
                        $i =1;
                        foreach($projects as $p){                              
                            if($p['today']<$p['expire']){
                                $Prid = Yii::$app->utility->encryptString($p['id']);                            
                                $status = Yii::$app->utility->encryptString($p['validity']);
                                $cid = Yii::$app->utility->encryptString($p['clientid']);
                                $editUrl = Yii::$app->homeUrl."manageproject/proposal/update?securekey=$menuid&key=$Prid&key1=$status";
                                $add_cbd = Yii::$app->homeUrl."manageproject/client-contact/create?securekey=$menuid&key=$cid";
                            ?>
                            <tr>
                                <td><?=$i?></td>

                                <td><?=$p['deptname']?></td>
                                <td><?=date('d-m-Y', strtotime($p['submissiondate']))?></td>
                                <td><?=$p['validity']?></td>
                                <td><?=$p['cost'] ?></td>
                                <td><?=date('d-m-Y', strtotime($p['expire']))?></td>                                
                                <td><!--<a href="<?//=$add_cbd?>" title="Add Client Detail">+</a>&nbsp; &nbsp;--><a href="<?=$editUrl?>" title="Edit Proposal Information"><img src='<?=Yii::$app->homeUrl?>images/edit.gif'></a>&nbsp; &nbsp;<a href="<?=$editUrl.'&view=1';?>" class="linkcolor">View</a></td>

                            </tr>	
                            <?php $i++;	
                            } else {
                                $Prid = Yii::$app->utility->encryptString($p['id']);                            
                                $status = Yii::$app->utility->encryptString($p['validity']);
                                $cid = Yii::$app->utility->encryptString($p['clientid']);
                                $editUrl = Yii::$app->homeUrl."manageproject/proposal/update?securekey=$menuid&key=$Prid&key1=$status";
                                $add_cbd = Yii::$app->homeUrl."manageproject/client-contact/create?securekey=$menuid&key=$cid";
                            ?>
                            <tr class="text-danger">
                                <td><?=$i?></td>

                                <td><?=$p['deptname']?></td>
                                <td><?=date('d-m-Y', strtotime($p['submissiondate']))?></td>
                                <td><?=$p['validity']?></td>
                                <td><?=$p['cost'] ?></td>
                                <td><?=date('d-m-Y', strtotime($p['expire']))?></td>                                
                                <td><!--<a href=<?//=$add_cbd?>" title="Add Client Detail">+</a>&nbsp; &nbsp;--><a href="<?=$editUrl?>" title="Edit Proposal Information"><img src='<?=Yii::$app->homeUrl?>images/edit.gif'></a>&nbsp; &nbsp;<a href="<?=$editUrl.'&view=1';?>" class="linkcolor">View</a></td>

                            </tr>	
                            <?php $i++;
                            }
                        }
                    }
                    ?>
                </tbody>
                
        </table>
    </div>
</div>


