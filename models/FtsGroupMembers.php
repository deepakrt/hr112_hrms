<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fts_group_members".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $dept_id
 * @property integer $e_id
 * @property string $is_active
 * @property string $created_date
 *
 * @property FtsGroupMaster $group
 * @property Employee $e
 */
class FtsGroupMembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fts_group_members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'dept_id', 'employee_code'], 'required'],
            [['group_id', 'employee_code'], 'integer'],
            [['is_active'], 'string'],
            [['created_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'dept_id' => 'Dept ID',
            'employee_code' => 'Employee Code',
            'is_active' => 'Is Active',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(FtsGroupMaster::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(Employee::className(), ['employee_code' => 'employee_code']);
    }
}
