<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = 'Categories';
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin();?> 
<input type="hidden" name="Category[fts_category_id]" id="fts_category_id" readonly="" />
<div class="row">
    <div class="col-sm-12">
        <h6><b><u>Add New Category</u></b></h6>
    </div>
    <div class="col-sm-3">
        <label>Category Name</label>
        <input type="text" name="Category[cat_name]" id="cat_name" class="form-control form-control-sm" required="" placeholder="Category Name" />
    </div>
    <div class="col-sm-4">
        <label>Description</label>
        <input type="text" name="Category[description]" id="cat_description" class="form-control form-control-sm" placeholder="Description" required="" />
    </div>
    <div class="col-sm-3">
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
                <th>Category Name</th>
                <th>Description</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($catys)){
                $i=1;
                foreach($catys as $c){ 
                    $fts_category_id = Yii::$app->utility->encryptString($c['fts_category_id']);
                ?>
            <tr>
                <td><?=$i?>
                    <input type="hidden" id="cat_id_<?=$i?>" value="<?=$fts_category_id?>" readonly="" />
                </td>
                <td id="cat_name_<?=$i?>"><?=$c['cat_name']?></td>
                <td id="description_<?=$i?>"><?=$c['description']?></td>
                <td><a href="javascript:void(0)" id='edit_category' data-key='<?=$i?>'><img src="<?=Yii::$app->homeUrl?>images/edit.gif" /></a></td>
            </tr>   
            <?php $i++;   }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Sr.</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Edit</th>
            </tr>
        </tfoot>
    </table>
</div>