<?php
namespace app\models;
use Yii;
class HrServiceDetails extends \yii\db\ActiveRecord
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
                [['employee_code','dept_id', 'designation_id', 'authority1', 'authority2'], 'required'],
                [['designation_id', 'dept_id', 'level', 'vpf_percentage', 'updated_by'], 'integer'],
                [['joining_date', 'effected_from', 'date_of_change'], 'safe'],
                [['is_active'], 'string'],
                [['employment_type', 'reason'], 'string', 'max' => 50],
                [['financial_year'], 'string', 'max' => 9],
                [['grade_pay_scale', 'basic_cons_pay'], 'string', 'max' => 25]
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
            'joining_date' => 'Joining Date',
            'designation_id' => 'Designation ID',
            'employment_type' => 'Employment Type',
            'authority1' => 'Authority1',
            'authority2' => 'Authority2',
            'dept_id' => 'Dept ID',
            'effected_from' => 'Effected From',
            'financial_year' => 'Financial Year',
            'grade_pay_scale' => 'Grade Pay Scale',
            'level' => 'Level',
            'basic_cons_pay' => 'Basic Cons Pay',
            'vpf_percentage' => 'Vpf Percentage',
            'reason' => 'Reason',
            'updated_by' => 'Updated By',
            'date_of_change' => 'Date Of Change',
            'is_active' => 'Is Active',
        ];
    }

}
