<?php

namespace app\models;
use Yii;
class Inventory extends \yii\db\ActiveRecord
{
    public $dept_id;
    public $dept_name;
    public $group;
    public $category;
    public $cost_centre;
    public $e_id;
    public $item;
    public $item_type;
    public $qty_required;
    public $units;
    public $remarks;
    public $purpose;
	
    public function rules()
    {
        return [
            [['dept_id','dept_name','group','category','e_id','item','qty_required','units','remarks','purpose'], 'required'],
			[['qty_required'], 'integer'],
            [['dept_name'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 255],
            [['dept_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dept_id' => 'Department',
            'dept_name' => 'Department',
            'e_id' => 'Employee',
            'remarks' => 'Remarks',
            //'is_active' => 'Is Active',
        ];
    }
}