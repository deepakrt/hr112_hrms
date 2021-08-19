<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoreMatReceiptTemp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-mat-receipt-temp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID')->textInput() ?>

    <?= $form->field($model, 'MRN_No')->textInput() ?>

    <?= $form->field($model, 'Receipt_date')->textInput() ?>

    <?= $form->field($model, 'PO_no')->textInput() ?>

    <?= $form->field($model, 'PO_Date')->textInput() ?>

    <?= $form->field($model, 'Indent_no')->textInput() ?>

    <?= $form->field($model, 'Dept_code')->textInput() ?>

    <?= $form->field($model, 'Cost_Centre_Code')->textInput() ?>

    <?= $form->field($model, 'Emp_code')->textInput() ?>

    <?= $form->field($model, 'Supplier_Code')->textInput() ?>

    <?= $form->field($model, 'Memo_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Memo_Date')->textInput() ?>

    <?= $form->field($model, 'Receipt_mode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Consignment_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Vehicle_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLASSIFICATION_CODE')->textInput() ?>

    <?= $form->field($model, 'ITEM_CAT_CODE')->textInput() ?>

    <?= $form->field($model, 'ITEM_CODE')->textInput() ?>

    <?= $form->field($model, 'QtyO')->textInput() ?>

    <?= $form->field($model, 'QtyS')->textInput() ?>

    <?= $form->field($model, 'QtyR')->textInput() ?>

    <?= $form->field($model, 'Measuring_Unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Rate_per_unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Sale_tax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Sale_tax_per')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Surcharge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ED')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SED')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Edu_Cess')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Cartage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Insurance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Packing_Forword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Octroi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Flag')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
