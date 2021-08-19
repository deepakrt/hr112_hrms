<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoreMatReceiptTempSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-mat-receipt-temp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'MRN_No') ?>

    <?= $form->field($model, 'Receipt_date') ?>

    <?= $form->field($model, 'PO_no') ?>

    <?= $form->field($model, 'PO_Date') ?>

    <?php // echo $form->field($model, 'Indent_no') ?>

    <?php // echo $form->field($model, 'Dept_code') ?>

    <?php // echo $form->field($model, 'Cost_Centre_Code') ?>

    <?php // echo $form->field($model, 'Emp_code') ?>

    <?php // echo $form->field($model, 'Supplier_Code') ?>

    <?php // echo $form->field($model, 'Memo_no') ?>

    <?php // echo $form->field($model, 'Memo_Date') ?>

    <?php // echo $form->field($model, 'Receipt_mode') ?>

    <?php // echo $form->field($model, 'Consignment_no') ?>

    <?php // echo $form->field($model, 'Vehicle_no') ?>

    <?php // echo $form->field($model, 'CLASSIFICATION_CODE') ?>

    <?php // echo $form->field($model, 'ITEM_CAT_CODE') ?>

    <?php // echo $form->field($model, 'ITEM_CODE') ?>

    <?php // echo $form->field($model, 'QtyO') ?>

    <?php // echo $form->field($model, 'QtyS') ?>

    <?php // echo $form->field($model, 'QtyR') ?>

    <?php // echo $form->field($model, 'Measuring_Unit') ?>

    <?php // echo $form->field($model, 'Rate_per_unit') ?>

    <?php // echo $form->field($model, 'Sale_tax') ?>

    <?php // echo $form->field($model, 'Sale_tax_per') ?>

    <?php // echo $form->field($model, 'Surcharge') ?>

    <?php // echo $form->field($model, 'ED') ?>

    <?php // echo $form->field($model, 'SED') ?>

    <?php // echo $form->field($model, 'Edu_Cess') ?>

    <?php // echo $form->field($model, 'Cartage') ?>

    <?php // echo $form->field($model, 'Insurance') ?>

    <?php // echo $form->field($model, 'Packing_Forword') ?>

    <?php // echo $form->field($model, 'Discount') ?>

    <?php // echo $form->field($model, 'Octroi') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Remark') ?>

    <?php // echo $form->field($model, 'Flag') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
