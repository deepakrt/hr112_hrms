<?php

namespace app\models;
use Yii;
class Unit extends \yii\db\ActiveRecord
{
    public $Unit_id;
    public $Unit_Name;

    public static function tableName()
    {
        return 'store_unit_master';
    }

    public function rules()
    {
        return [
            [['Unit_Name'], 'required'],
            [['Unit_Name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Unit_Name' => 'Unit Name',
        ];
    }
}
