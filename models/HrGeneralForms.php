<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hr_general_forms".
 *
 * @property integer $id
 * @property integer $e_id
 * @property string $type
 * @property string $entry_type
 * @property string $entry_date
 * @property string $entry_time
 * @property string $exit_time
 * @property string $reason
 * @property integer $approved_by
 * @property string $approved_on
 * @property string $submitted_on
 * @property string $status
 * @property string $is_active
 *
 * @property Employee $e
 * @property RbacEmployee $approvedBy
 */
class HrGeneralForms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_general_forms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'type', 'entry_date', 'entry_time', 'exit_time', 'reason', 'submitted_on', 'status'], 'required'],
            [['e_id', 'approved_by'], 'integer'],
            [['type', 'entry_type', 'status', 'is_active'], 'string'],
            [['entry_date', 'approved_on', 'submitted_on'], 'safe'],
            [['entry_time', 'exit_time'], 'string', 'max' => 10],
            [['reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'e_id' => 'E ID',
            'type' => 'Type',
            'entry_type' => 'Entry Type',
            'entry_date' => 'Entry Date',
            'entry_time' => 'Entry Time',
            'exit_time' => 'Exit Time',
            'reason' => 'Reason',
            'approved_by' => 'Approved By',
            'approved_on' => 'Approved On',
            'submitted_on' => 'Submitted On',
            'status' => 'Status',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(Employee::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(RbacEmployee::className(), ['map_id' => 'approved_by']);
    }
}
