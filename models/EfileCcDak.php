<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_cc_dak".
 *
 * @property int $cc_id
 * @property int $file_id
 * @property int $movement_id
 * @property string $emp_code
 * @property string $created_date
 * @property string|null $last_updated
 * @property string $is_active
 *
 * @property EfileDak $file
 * @property EfileDakMovement $movement
 */
class EfileCcDak extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'efile_cc_dak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'movement_id', 'emp_code', 'created_date'], 'required'],
            [['file_id', 'movement_id'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['is_active'], 'string'],
            [['emp_code'], 'string', 'max' => 200],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cc_id' => 'Cc ID',
            'file_id' => 'File ID',
            'movement_id' => 'Movement ID',
            'emp_code' => 'Emp Code',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'is_active' => 'Is Active',
        ];
    }

}
