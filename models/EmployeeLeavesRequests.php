<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_leaves_requests".
 *
 * @property integer $emp_leave_id
 * @property integer $e_id
 * @property string $leave_reason
 * @property string $availing_for_LTC
 * @property string $contact_address
 * @property string $contact_no
 * @property integer $leave_type
 * @property string $whetherhalfday
 * @property string $from
 * @property string $till
 * @property string $status
 * @property integer $approved_by
 * @property string $remarks
 * @property string $applied_date
 * @property string $action_date
 *
 * @property MasterLeaveType $leaveType
 * @property Employee $e
 */
class EmployeeLeavesRequests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_employee_leaves_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_code', 'leave_type','leave_reason', 'contact_address', 'contact_no', 'leave_type', 'req_from_date', 'req_to_date'], 'required'],
            [['employee_code',  'approved_by'], 'integer'],
            [['availing_for_LTC', 'whetherhalfday', 'status'], 'string'],
            [['req_from_date', 'req_to_date', ], 'safe'],
            [['leave_reason', 'contact_address', 'remarks'], 'string', 'max' => 255],
            [['contact_no'], 'integer', 'message' => 'Please enter valid contact number.'],
            [['contact_no'], 'integer', 'min' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emp_leave_id' => 'Emp Leave ID',
            'e_id' => 'E ID',
            'leave_reason' => 'Reason',
            'availing_for_LTC' => 'Availing For  LTC',
            'contact_address' => 'Contact Address',
            'contact_no' => 'Contact No',
            'leave_type' => 'Leave Type',
            'whetherhalfday' => 'Whetherhalfday',
            'req_from_date' => 'From',
            'req_to_date' => 'Till',
            'status' => 'Status',
            'approved_by' => 'Approved By',
            'remarks' => 'Remarks',
            'applied_date' => 'Applied Date',
            'action_date' => 'Action Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(MasterLeaveType::className(), ['lt_id' => 'leave_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(Employee::className(), ['e_id' => 'e_id']);
    }
}
