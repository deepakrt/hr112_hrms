<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_group_members".
 *
 * @property integer $id
 * @property integer $dak_group_id
 * @property string $employee_code
 * @property string $group_role
 * @property string $created_date
 * @property string $is_active
 */
class EfileDakGroupMembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_group_members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dak_group_id', 'employee_code', 'group_role', 'created_date', 'is_active'], 'required'],
            [['dak_group_id'], 'integer'],
            [['group_role', 'is_active'], 'string'],
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
            'employee_code' => 'Employee Code',
            'group_role' => 'Group Role',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
        ];
    }
}
