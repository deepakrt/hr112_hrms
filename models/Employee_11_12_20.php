<?php
namespace app\models;
use Yii;
class Employee extends \yii\db\ActiveRecord{
    
    public $employee_id,$fname; public $lname;public $gender;public $employment_type;public $dob;public $contact;public $address;public $city;public $state;public $zip;public $p_address;public $p_city;public $p_state;public $p_zip;public $joining_date;public $marital_status;public $blood_group;public $is_active;public $emergency_contact;public $created_date;public $contact1;public $contact2;public $personal_email;public $designation;public $bank_ac;public $bank_name;public $bank_ifsc;public $emp_image;public $emp_signature;public $dept_id, $dept_name, $desg_id, $desg_name, $authority1, $authority2, $effected_from, $grade_pay_scale,$emplevel,$basic_cons_pay, $pan_number; 
     
    //public static function tableName(){ return 'employee'; }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personal_email','employee_id', 'fname', 'gender'], 'required'],
            [['dob', 'joining_date', 'created_date'], 'safe'],
            [[ 'auth1','auth2', 'grade_pay_scale'], 'integer'],
            [['username', 'employment_type'], 'string', 'max' => 50],
            [['fname', 'lname'], 'string', 'max' => 90],
            [['phone', 'emergency_phone'], 'string', 'max' => 20],
            [['pan_number'], 'string', 'max' => 10],
            [['emp_image', 'emp_signature','marital_status', 'blood_group','gender', 'is_active','address', 'city', 'state', 'zip', 'contact', 'p_address', 'p_city', 'p_state', 'p_zip', 'p_contact'], 'string', 'max' => 255],
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'basic_cons_pay' => 'Basic Pay',
            'emplevel' => 'Level',
            'grade_pay_scale' => 'Grade Pay Scale',
            'effected_from' => 'Effected From',
            'dept_name' => 'Department',
            'e_id' => 'ID',
            'employee_id' => 'Employee ID',
            'username' => 'Username',
            'bank_ac' => 'Account No.',
            'dept_id' => 'Department',
            'desg_name' => 'Designation',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'phone' => 'Phone',
            'emergency_phone' => 'Emergency Phone',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'contact1' => 'Landline / Mobile',
            'contact2' => 'Landline / Mobile',
            'contact' => 'Contact',
            'p_address' => 'Address',
            'p_city' => 'City',
            'p_state' => 'State',
            'p_zip' => 'Zip',
            'p_contact' => 'Contact',
            'joining_date' => 'Joining Date',
            //'designation_id' => 'Designation',
            'employment_type' => 'Employment Type',
            'marital_status' => 'Marital Status',
            'blood_group' => 'Blood Group',
            'is_active' => 'Is Active',
            'emp_image' => 'Emp Image',
            'emp_signature' => 'Emp Signature',
            'created_date' => 'Created Date',
            'personal_email' => 'Personal Email ID',
            'authority1'=>'Reporting Authority',
            'authority2'=>'Head of Department',
            'desg_id'=>'Select Designation',
            'pan_number'=>'PAN Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(Designation::className(), ['desg_id' => 'designation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeLeaveDetails()
    {
        return $this->hasOne(EmployeeLeaveDetails::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeQualifications()
    {
        return $this->hasMany(EmployeeQualification::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacEmployees()
    {
        return $this->hasMany(RbacEmployee::className(), ['e_id' => 'e_id']);
    }
}
