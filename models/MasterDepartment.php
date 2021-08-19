<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_department".
 *
 * @property integer $dept_id
 * @property string $dept_name
 * @property string $dept_desc
 * @property string $is_active
 *
 * @property RbacEmployee[] $rbacEmployees
 */
class MasterDepartment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dept_name', 'dept_desc', 'is_active'], 'required'],
            [['is_active'], 'string'],
            [['dept_name'], 'string', 'max' => 100],
            [['dept_desc'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dept_id' => 'Dept ID',
            'dept_name' => 'Dept Name',
            'dept_desc' => 'Dept Desc',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacEmployees()
    {
        return $this->hasMany(RbacEmployee::className(), ['dept_id' => 'dept_id']);
    }
}
