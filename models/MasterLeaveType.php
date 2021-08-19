<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_leave_type".
 *
 * @property integer $lt_id
 * @property string $label
 * @property string $desc
 * @property string $is_active
 *
 * @property EmployeeLeavesRequests[] $employeeLeavesRequests
 * @property MasterLeavesChart $masterLeavesChart
 */
class MasterLeaveType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_master_leave_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'desc'], 'required'],
            [['is_active'], 'string'],
            [['label'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lt_id' => 'Lt ID',
            'label' => 'Label',
            'desc' => 'Desc',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeLeavesRequests()
    {
        return $this->hasMany(EmployeeLeavesRequests::className(), ['leave_type' => 'lt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterLeavesChart()
    {
        return $this->hasOne(MasterLeavesChart::className(), ['leave_type' => 'lt_id']);
    }
}
