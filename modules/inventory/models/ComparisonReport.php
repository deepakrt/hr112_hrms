<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "store_mat_receipt_temp".
 *
 * @property integer $id
 * @property integer $Supplier_Code
 * @property integer $CLASSIFICATION_CODE
 * @property integer $ITEM_CAT_CODE
 * @property integer $ITEM_CODE
 * @property integer $Qty 
 * @property string $Amount
 * @property string $remarks
 * @property string $tax

 */
class ComparisonReport extends \yii\db\ActiveRecord
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
        return 'store_camp_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'Supplier_Code', 'CLASSIFICATION_CODE', 'ITEM_CAT_CODE', 'ITEM_CODE','tax','Qty'], 'integer'],
             [[ 'Supplier_Code', 'CLASSIFICATION_CODE', 'ITEM_CAT_CODE', 'ITEM_CODE','Amount','Qty'], 'required'],
            [['sdate'], 'safe'],           
            [[ 'Amount'], 'number'],
            [[ 'remarks'], 'string', 'max' => 5000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',            
            'Supplier_Code' => 'Supplier Name',         
            'CLASSIFICATION_CODE' => 'Group',
            'ITEM_CAT_CODE' => 'Category',
            'ITEM_CODE' => 'Item',
            'Qty' => 'Qty. As Per Bill',            
            'remarks' => 'remarks', 
            'tax' => 'Tax',   
             'Amount' => 'Amount',
        ];
    }
}
