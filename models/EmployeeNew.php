<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $e_id
 * @property string $username
 * @property string $fname
 * @property string $lname
 * @property string $gender
 * @property string $dob
 * @property string $phone
 * @property string $emergency_phone
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $contact
 * @property string $p_address
 * @property string $p_city
 * @property string $p_state
 * @property string $p_zip
 * @property string $p_contact
 * @property string $joining_date
 * @property integer $designation_id
 * @property string $employment_type
 * @property string $marital_status
 * @property string $blood_group
 * @property string $is_active
 * @property integer $authority1
 * @property integer $authority2
 * @property string $emp_image
 * @property string $emp_signature
 * @property string $created_date
 *
 * @property HrMasterDesignation $designation
 * @property FtsGroupMembers[] $ftsGroupMembers
 * @property HrEmployeeFamilyDetails[] $hrEmployeeFamilyDetails
 * @property HrEmployeeLeavesDetailChart[] $hrEmployeeLeavesDetailCharts
 * @property HrEmployeeLeavesRequests[] $hrEmployeeLeavesRequests
 * @property HrEmployeeQualification[] $hrEmployeeQualifications
 * @property HrLeaveCardDetails[] $hrLeaveCardDetails
 * @property HrLeaveCardDetails[] $hrLeaveCardDetails0
 * @property MenuMapping[] $menuMappings
 * @property RbacEmployee[] $rbacEmployees
 */
class EmployeeNew extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'fname', 'gender', 'dob', 'phone', 'emergency_phone', 'address', 'city', 'state', 'zip', 'contact', 'p_address', 'p_city', 'p_state', 'p_zip', 'joining_date', 'employment_type', 'marital_status', 'is_active', 'authority1', 'authority2', 'created_date'], 'required'],
            [['gender', 'is_active'], 'string'],
            [['dob', 'joining_date', 'created_date'], 'safe'],
            [['designation_id', 'authority1', 'authority2'], 'integer'],
            [['username', 'employment_type'], 'string', 'max' => 50],
            [['fname', 'lname'], 'string', 'max' => 90],
            [['phone', 'emergency_phone'], 'string', 'max' => 20],
            [['address', 'city', 'state', 'zip', 'contact', 'p_address', 'p_city', 'p_state', 'p_zip', 'p_contact'], 'string', 'max' => 255],
            [['emp_image', 'emp_signature'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'e_id' => 'E ID',
            'username' => 'Username',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'phone' => 'Phone',
            'emergency_phone' => 'Emergency Phone',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'contact' => 'Contact',
            'p_address' => 'P Address',
            'p_city' => 'P City',
            'p_state' => 'P State',
            'p_zip' => 'P Zip',
            'p_contact' => 'P Contact',
            'joining_date' => 'Joining Date',
            'designation_id' => 'Designation ID',
            'employment_type' => 'Employment Type',
            'marital_status' => 'Marital Status',
            'blood_group' => 'Blood Group',
            'is_active' => 'Is Active',
            'authority1' => 'Authority1',
            'authority2' => 'Authority2',
            'emp_image' => 'Emp Image',
            'emp_signature' => 'Emp Signature',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(HrMasterDesignation::className(), ['desg_id' => 'designation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFtsGroupMembers()
    {
        return $this->hasMany(FtsGroupMembers::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeFamilyDetails()
    {
        return $this->hasMany(HrEmployeeFamilyDetails::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeLeavesDetailCharts()
    {
        return $this->hasMany(HrEmployeeLeavesDetailChart::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeLeavesRequests()
    {
        return $this->hasMany(HrEmployeeLeavesRequests::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeQualifications()
    {
        return $this->hasMany(HrEmployeeQualification::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrLeaveCardDetails()
    {
        return $this->hasMany(HrLeaveCardDetails::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrLeaveCardDetails0()
    {
        return $this->hasMany(HrLeaveCardDetails::className(), ['updated_by' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuMappings()
    {
        return $this->hasMany(MenuMapping::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacEmployees()
    {
        return $this->hasMany(RbacEmployee::className(), ['e_id' => 'e_id']);
    }
}
