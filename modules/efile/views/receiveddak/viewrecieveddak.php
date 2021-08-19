<?php
$this->title= "प्राप्त डाक विवरण दर्ज करें / Enter Received Dak Details";
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<div class='row'>
    <div class='col-sm-12'>
        <h6><b><span class='hindishow'>रसीद जानकारी</span> / Receipt Information:-</b></h6>
        <table class="table table-bordered" style="font-size: 14px;">
            <tr>
                <td colspan="2"><b><span class="hindishow12">रसीद संख्या तथा दिनांक </span>/ Receipt No. & Date</b><br><?=$recieveddak['dak_number']?> Dated <?=date('d-M Y', strtotime($recieveddak['rec_date']))?></td>
            </tr>
            <tr>
                <td><b><span class="hindishow12">से प्राप्त किया / </span>Received From </b><br><?=$recieveddak['rec_from'].", ".$recieveddak['org_address']?></td>
            </tr>
            <?php if(!empty($recieveddak['dak_summary'])){?>
            <tr>
                <td colspan="2" class='text-justify'><b><span class="hindishow12">सारांश </span>/ Summary</b><br><?=$recieveddak['dak_summary']?></td>
            </tr>
            <?php } ?>
            <?php if(!empty($recieveddak['dak_remarks'])){?>
            <tr>
                <td colspan="2" class='text-justify'><b><span class="hindishow12">टिप्पणी / </span>Remarks</b><br><?=$recieveddak['dak_remarks']?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="2"><b><span class="hindishow12">अग्रेषित तिथि / </span>Forwarded On: </b><?=date('d-M Y', strtotime($recieveddak['forwarded_date']))?></td>
            </tr>
        </table>
        <hr class='hrline'>
    </div>
</div>
<?php 

echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/add_new_dak.php', ['recieveddak'=>$recieveddak, 'model'=>$model, 'menuid'=>$menuid]);
?>