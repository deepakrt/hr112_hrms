<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_group_members_remarks".
 *
 * @property integer $id
 * @property integer $dak_group_id
 * @property integer $file_id
 * @property string $employee_code
 * @property string $group_role
 * @property string $remarks
 * @property string $status
 * @property string $created_date
 * @property string $last_updated_date
 * @property string $is_active
 */
class EfileDakGroupMembersRemarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_group_members_remarks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dak_group_id', 'file_id', 'employee_code', 'group_role', 'remarks', 'status', 'created_date'], 'required'],
            [['dak_group_id', 'file_id'], 'integer'],
            [['group_role', 'remarks', 'status', 'is_active'], 'string'],
            [['created_date', 'last_updated_date'], 'safe'],
            [['employee_code'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dak_group_id' => 'Dak Group ID',
            'file_id' => 'File ID',
            'employee_code' => 'Employee Code',
            'group_role' => 'Group Role',
            'remarks' => 'Remarks',
            'status' => 'D: Draft, S:Submit, CHD: Chairman Draft for members, CHF : Chairman Final Remarks',
            'created_date' => 'Created Date',
            'last_updated_date' => 'Last Updated Date',
            'is_active' => 'Is Active',
        ];
    }
}
