<?php

namespace app\models;
use Yii;
class Department extends \yii\db\ActiveRecord
{
    public $dept_id;
    public $dept_name;
    public $dept_desc;
    public $is_active;
	
    public function rules()
    {
        return [
            [['dept_id', 'dept_name',  'dept_desc','is_active'], 'required'],
            [['dept_name'], 'string', 'max' => 50],
            [['dept_desc'], 'string', 'max' => 255],
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
            'dept_desc' => 'Description',
            'is_active' => 'Is Active',
        ];
    }
}