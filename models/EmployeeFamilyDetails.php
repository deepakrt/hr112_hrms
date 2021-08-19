<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_family_details".
 *
 * @property integer $ef_id
 * @property integer $e_id
 * @property string $m_name
 * @property integer $relation_id
 * @property string $marital_status
 * @property string $m_dob
 * @property string $handicap
 * @property string $handicap_percentage
 * @property string $monthly_income
 * @property string $address
 * @property string $p_address
 * @property string $document_type
 * @property string $document_path
 * @property string $status
 * @property string $created_date
 * @property string $modified_date
 *
 * @property Employee $e
 * @property MasterFamilyRelations $relation
 */
class EmployeeFamilyDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_employee_family_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'm_name', 'relation_id', 'marital_status', 'm_dob','handicap', 'status', 'created_date'], 'required'],
            [['e_id','monthly_income', ], 'integer'],
            [['m_dob', 'created_date', 'modified_date'], 'safe'],
            [['handicap', 'status'], 'string'],
            [['m_name'], 'string', 'max' => 200],
//            [['marital_status'], 'string', 'max' => 20],
            [['handicap_percentage'], 'integer', 'max' => 100],
            [[ 'document_path'], 'string', 'max' => 100],
            [['address', 'p_address'], 'string', 'max' => 255],
//            [['document_type'], 'string', 'max' => 50],
//            ['handicap', 'afsfdf'],
//            [['handicate_type','handicap_percentage'], 'required', 'when' =>
//                function($model) 
//                {
//                    echo "<pre>";print_r($model); die;
//                    if (($model->handicap) == 'Y')
//                    {
//                        echo "asdf"; die;
//                        return true;
//                    }
//                    else
//                    {
//                        return false;
//                    }
//                },  'enableClientValidation' => TRUE,],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ef_id' => 'Ef ID',
            'e_id' => 'E ID',
            'm_name' => 'Member Name',
            'relation_id' => 'Relation With Employee',
            'marital_status' => 'Marital Status',
            'm_dob' => 'Date of Birth',
            'handicap' => 'Is Handicap',
            'handicate_type' => 'Select Handicap Type',
            'handicap_percentage' => 'Handicap Percentage',
            'monthly_income' => 'Monthly Income',
            'address' => 'Home Address',
            'p_address' => 'Postal Address',
            'document_type' => 'Document Type',
            'document_path' => 'Browse File (Only .png,.jpg,.jpeg allowed)',
            'status' => 'Status',
            'created_date' => 'Added On',
            'modified_date' => 'Last Modified',
        ];
    }
    public function afsfdf($attributes,$param) 
    {
        echo "asdf"; die;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(Employee::className(), ['e_id' => 'e_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getRelation()
//    {
//        return $this->hasOne(MasterFamilyRelations::className(), ['relation_id' => 'relation_id']);
//    }
}
