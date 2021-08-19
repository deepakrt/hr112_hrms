<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StoreMatReceiptTempSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Store Mat Receipt Temps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-mat-receipt-temp-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Store Mat Receipt Temp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'MRN_No',
            'Receipt_date',
            'PO_no',
            'PO_Date',
            // 'Indent_no',
            // 'Dept_code',
            // 'Cost_Centre_Code',
            // 'Emp_code',
            // 'Supplier_Code',
            // 'Memo_no',
            // 'Memo_Date',
            // 'Receipt_mode',
            // 'Consignment_no',
            // 'Vehicle_no',
            // 'CLASSIFICATION_CODE',
            // 'ITEM_CAT_CODE',
            // 'ITEM_CODE',
            // 'QtyO',
            // 'QtyS',
            // 'QtyR',
            // 'Measuring_Unit',
            // 'Rate_per_unit',
            // 'Sale_tax',
            // 'Sale_tax_per',
            // 'Surcharge',
            // 'ED',
            // 'SED',
            // 'Edu_Cess',
            // 'Cartage',
            // 'Insurance',
            // 'Packing_Forword',
            // 'Discount',
            // 'Octroi',
            // 'Description',
            // 'Remark',
            // 'Flag',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
