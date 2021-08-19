<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rbac_employee".
 *
 * @property int $map_id
 * @property string $username
 * @property string $password
 * @property int $role_id
 * @property string $created_date
 * @property string $is_active
 * @property string|null $employee_code
 *
 * @property MasterRoles $role
 */
class RbacEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rbac_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['username', 'password', 'role_id', 'created_date'], 'required'],
//            [['role_id'], 'integer'],
//            [['created_date'], 'safe'],
//            [['is_active'], 'string'],
//            [['username'], 'string', 'max' => 90],
//            [['password', 'employee_code'], 'string', 'max' => 100],
//            [['username'], 'unique'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'map_id' => 'Map ID',
            'username' => 'Username',
            'password' => 'Password',
            'role_id' => 'Role ID',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
            'employee_code' => 'Employee Code',
        ];
    }

   
}
