<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_group_member_approval".
 *
 * @property integer $id
 * @property integer $dak_group_id
 * @property integer $file_id
 * @property string $employee_code
 * @property string $remarks_final_status
 * @property string $created_date
 * @property string $is_active
 */
class EfileDakGroupMemberApproval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_group_member_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dak_group_id', 'file_id', 'employee_code', 'remarks_final_status', 'created_date'], 'required'],
            [['dak_group_id', 'file_id'], 'integer'],
            [['remarks_final_status', 'is_active'], 'string'],
            [['created_date'], 'safe'],
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
            'remarks_final_status' => 'Remarks Final Status',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
        ];
    }
}
