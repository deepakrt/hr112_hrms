<?php

namespace app\models;
use Yii;
class Supplier extends \yii\db\ActiveRecord
{
    public $Supplier_Code;
    public $Supplier_name;
    public $Supplier_address;
    public $Phone_no;
    public $Category;
    public $is_active;
	
public static function tableName()
    {
        return 'store_supplier_master';
    }

    public function rules()
    {
        return [
            [['Supplier_name','Supplier_address','Phone_no','Category'], 'required'],
            [['Supplier_name'], 'string', 'max' => 50],
            [['Phone_no'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Supplier_name' => 'Supplier Name',
            'Supplier_address' => 'Supplier Address',
            'Phone_no' => 'Supplier Phone Number',
            'Category' => 'Category',
        ];
    }
}
