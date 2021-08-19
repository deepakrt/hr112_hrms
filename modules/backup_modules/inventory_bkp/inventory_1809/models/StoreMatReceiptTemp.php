<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "store_mat_receipt_temp".
 *
 * @property integer $ID
 * @property integer $MRN_No
 * @property string $Receipt_date
 * @property integer $PO_no
 * @property string $PO_Date
 * @property integer $Indent_no
 * @property integer $Dept_code
 * @property integer $Cost_Centre_Code
 * @property integer $Emp_code
 * @property integer $Supplier_Code
 * @property string $Memo_no
 * @property string $Memo_Date
 * @property string $Receipt_mode
 * @property string $Consignment_no
 * @property string $Vehicle_no
 * @property integer $CLASSIFICATION_CODE
 * @property integer $ITEM_CAT_CODE
 * @property integer $ITEM_CODE
 * @property integer $QtyO
 * @property integer $QtyS
 * @property integer $QtyR
 * @property string $Measuring_Unit
 * @property string $Rate_per_unit
 * @property string $Sale_tax
 * @property string $Sale_tax_per
 * @property string $Surcharge
 * @property string $ED
 * @property string $SED
 * @property string $Edu_Cess
 * @property string $Cartage
 * @property string $Insurance
 * @property string $Packing_Forword
 * @property string $Discount
 * @property string $Octroi
 * @property string $Description
 * @property string $Remark
 * @property integer $Flag
 */
class StoreMatReceiptTemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $phoneno;
	public $item_type;
	public $units;
	//public $item_description;
	public $address;
    public static function tableName()
    {
        return 'store_mat_receipt_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'MRN_No', 'PO_no', 'Indent_no', 'Dept_code', 'Cost_Centre_Code', 'Emp_code', 'Supplier_Code', 'CLASSIFICATION_CODE', 'ITEM_CAT_CODE', 'ITEM_CODE', 'Flag'], 'integer'],
             [['MRN_No', 'Receipt_date', 'PO_no','Cost_Centre_Code','Description', 'Indent_no', 'Emp_code', 'Supplier_Code', 'Memo_no', 'Memo_Date', 'Receipt_mode', 'Consignment_no', 'Vehicle_no', 'CLASSIFICATION_CODE', 'ITEM_CAT_CODE', 'ITEM_CODE', 'QtyO', 'QtyR', 'Measuring_Unit', 'Rate_per_unit', 'Sale_tax','Sale_tax_per','Edu_Cess','SED','ED','Packing_Forword','Discount','Cartage','Insurance','address','Surcharge','Octroi','Remark'], 'required'],
            [['Receipt_date', 'PO_Date', 'Memo_Date'], 'safe'],
            [['Rate_per_unit', 'Sale_tax', 'Sale_tax_per', 'Surcharge', 'ED', 'SED', 'Edu_Cess', 'Cartage', 'Insurance', 'Packing_Forword', 'Discount', 'Octroi'], 'number'],
            [['Memo_no', 'Receipt_mode', 'Consignment_no', 'Vehicle_no', 'Measuring_Unit'], 'string', 'max' => 50],
            [['Description', 'Remark'], 'string', 'max' => 5000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MRN_No' => 'Mrn  No',
            'Receipt_date' => 'Receipt Date',
            'PO_no' => 'P.O. No',
            'PO_Date' => 'P.O.  Date',
            'Indent_no' => 'Indent No',
            'Dept_code' => 'Dept Code',
            'Cost_Centre_Code' => 'Cost  Centre  Code',
            'Emp_code' => 'Empyee',
            'Supplier_Code' => 'Supplier Name',
            'Memo_no' => 'Bill No',
            'Memo_Date' => 'Bill  Date',
            'Receipt_mode' => 'Receipt Mode',
            'Consignment_no' => 'Consignment No',
            'Vehicle_no' => 'Vehicle No',
            'CLASSIFICATION_CODE' => 'Group',
            'ITEM_CAT_CODE' => 'Category',
            'ITEM_CODE' => 'Item',
            'QtyO' => 'Qty. As Per Bill',
            'QtyS' => 'Qty.short/Damage',
            'QtyR' => 'Qty. Received',
            'Measuring_Unit' => 'Measuring  Unit',
            'Rate_per_unit' => 'Rate Per Unit',
            'Sale_tax' => 'Sale Tax',
            'Sale_tax_per' => 'Sale Tax%',
            'Surcharge' => 'Surcharge',
            'ED' => 'Ed',
            'SED' => 'Sed',
            'Edu_Cess' => 'Edu  Cess',
            'Cartage' => 'Cartage',
            'Insurance' => 'Insurance',
            'Packing_Forword' => 'Packing  Forword',
            'Discount' => 'Discount',
            'Octroi' => 'Octroi',
            'Description' => 'Description',
            'Remark' => 'Remark',
            'Flag' => 'Flag',
        ];
    }
}
