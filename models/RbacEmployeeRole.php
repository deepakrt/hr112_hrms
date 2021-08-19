<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rbac_employee_role".
 *
 * @property integer $id
 * @property integer $employee_code
 * @property integer $role_id
 * @property string $is_active
 */
class RbacEmployeeRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rbac_employee_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_code', 'role_id'], 'required'],
            [['employee_code', 'role_id'], 'integer'],
            [['is_active'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_code' => 'Employee Code',
            'role_id' => 'Role ID',
            'is_active' => 'Is Active',
        ];
    }
}
