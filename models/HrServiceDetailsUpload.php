<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hr_service_details".
 *
 * @property int $ser_id
 * @property string $employee_code
 * @property int|null $designation_id
 * @property string $employment_type
 * @property string|null $authority1
 * @property string|null $authority1_dept_id
 * @property string|null $authority2
 * @property string|null $authority2_dept_id
 * @property int $dept_id
 * @property string $updated_by
 * @property string $date_of_change
 * @property string $is_active
 *
 * @property MasterDesignation $designation
 * @property MasterDepartment $dept
 */
class HrServiceDetailsUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_service_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_code', 'employment_type', 'dept_id', 'updated_by', 'date_of_change'], 'required'],
            [['designation_id', 'dept_id'], 'integer'],
            [['date_of_change'], 'safe'],
            [['is_active'], 'string'],
            [['employee_code',  'authority1_dept_id', 'authority2_dept_id', 'updated_by'], 'string', 'max' => 100],
            [['employment_type'], 'string', 'max' => 50],           
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ser_id' => 'Ser ID',
            'employee_code' => 'Employee Code',
            'designation_id' => 'Designation ID',
            'employment_type' => 'Employment Type',
            'authority1' => 'Authority1',
            'authority1_dept_id' => 'Authority1 Dept ID',
            'authority2' => 'Authority2',
            'authority2_dept_id' => 'Authority2 Dept ID',
            'dept_id' => 'Dept ID',
            'updated_by' => 'Updated By',
            'date_of_change' => 'Date Of Change',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[Designation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(MasterDesignation::className(), ['desg_id' => 'designation_id']);
    }

    /**
     * Gets query for [[Dept]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDept()
    {
        return $this->hasOne(MasterDepartment::className(), ['dept_id' => 'dept_id']);
    }
}
