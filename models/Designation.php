<?php

namespace app\models;
use Yii;
class Designation extends \yii\db\ActiveRecord
{
    public $desg_id;
    public $desg_name;
    public $desg_desc;
    public $is_active;
	
    public function rules()
    {
        return [
            [['desg_id', 'desg_name',  'desg_desc','is_active'], 'required'],
            [['desg_name'], 'string', 'max' => 50],
            [['desg_desc'], 'string', 'max' => 255],
            [['desg_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'desg_id' => 'Designation',
            'desg_name' => 'Designation',
            'desg_desc' => 'Description',
            'is_active' => 'Is Active',
        ];
    }
}