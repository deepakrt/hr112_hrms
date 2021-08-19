<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StoreMatReceiptTemp */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Store Mat Receipt Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-mat-receipt-temp-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'MRN_No',
            'Receipt_date',
            'PO_no',
            'PO_Date',
            'Indent_no',
            'Dept_code',
            'Cost_Centre_Code',
            'Emp_code',
            'Supplier_Code',
            'Memo_no',
            'Memo_Date',
            'Receipt_mode',
            'Consignment_no',
            'Vehicle_no',
            'CLASSIFICATION_CODE',
            'ITEM_CAT_CODE',
            'ITEM_CODE',
            'QtyO',
            'QtyS',
            'QtyR',
            'Measuring_Unit',
            'Rate_per_unit',
            'Sale_tax',
            'Sale_tax_per',
            'Surcharge',
            'ED',
            'SED',
            'Edu_Cess',
            'Cartage',
            'Insurance',
            'Packing_Forword',
            'Discount',
            'Octroi',
            'Description',
            'Remark',
            'Flag',
        ],
    ]) ?>

</div>
