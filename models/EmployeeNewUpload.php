<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $e_id
 * @property int|null $employee_code
 * @property string $email_id
 * @property string $fname
 * @property string|null $lname
 * @property string|null $name_hindi
 * @property string|null $gender
 * @property string|null $dob
 * @property string|null $phone
 * @property string|null $emergency_phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $contact
 * @property string|null $p_address
 * @property string|null $p_city
 * @property string|null $p_state
 * @property string|null $p_zip
 * @property string|null $p_contact
 * @property string|null $pan_number
 * @property string|null $marital_status
 * @property string|null $blood_group
 * @property string $is_active
 * @property string|null $emp_image
 * @property string|null $emp_signature
 * @property string $created_date
 */
class EmployeeNewUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_code'], 'integer'],
            [['email_id', 'fname', 'created_date'], 'required'],
            [['gender', 'is_active'], 'string'],
            [['dob', 'created_date'], 'safe'],
            [['email_id'], 'string', 'max' => 50],
            [['fname', 'lname'], 'string', 'max' => 90],
            [['name_hindi', 'address', 'city', 'state', 'zip', 'contact', 'p_address', 'p_city', 'p_state', 'p_zip', 'p_contact'], 'string', 'max' => 255],
            [['phone', 'emergency_phone', 'marital_status'], 'string', 'max' => 20],
            [['pan_number', 'blood_group'], 'string', 'max' => 10],
            [['emp_image', 'emp_signature'], 'string', 'max' => 100],
            [['employee_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'e_id' => 'E ID',
            'employee_code' => 'Employee Code',
            'email_id' => 'Email ID',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'name_hindi' => 'Name Hindi',
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
            'pan_number' => 'Pan Number',
            'marital_status' => 'Marital Status',
            'blood_group' => 'Blood Group',
            'is_active' => 'Is Active',
            'emp_image' => 'Emp Image',
            'emp_signature' => 'Emp Signature',
            'created_date' => 'Created Date',
        ];
    }
}
