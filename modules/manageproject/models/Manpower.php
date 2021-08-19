<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\manageproject\models\Qualification;
use app\modules\manageproject\models\Manpowermapping;
use app\modules\manageproject\models\Ordermaster;
use common\models\User;

/**
 * This is the model class for table "manpower".
 *
 * @property integer $activeuser
 * @property integer $deleted
 * @property integer $id
 * @property string $sessionid
 * @property string $updatedon
 * @property string $name
 * @property string $doj
 * @property string $dor
 * @property string $dob
 * @property integer $designationid
 * @property string $email
 * @property integer $phone
 * @property integer $salary
 * @property string $technologyid
 * @property string $qualification
 * @property integer $totalexperience
 * @property integer $cdacexperience
 * @property string $doresign
 */
class Manpower extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manpower}}';
        
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activeuser', 'sessionid', 'name', 'doj', 'dor', 'dob', 'designationid', 'email', 'phone', 'salary', 'technologyid', 'qualification', 'totalexperience', 'cdacexperience', 'empcode', 'emptype', 'gender', 'stafftype', 'grade', 'payband', 'scale', 'category', 'dope', 'superannuationdate',  'gradepay'], 'required', 'message' =>'Required!'],
            [['activeuser',  'cdacdeptid'], 'integer', 'message' =>'Sorry, some error occured!'],
            [['designationid', 'coi', 'gradepay'], 'integer', 'message' =>'Please select!'],
            [['salary', 'totalexperience', 'cdacexperience', 'empcode'], 'integer', 'message' =>'Enter valid numbers!'],
            [['totalexperience'],'number','min'=>0,'max'=>2147483647, 'message' =>'Check the value!', 'tooBig' => 'Must not be greater than 2147483647.', 'tooSmall' => 'Must not be less than 0.'],
            [['cdacexperience'],'number','min'=>0,'max'=>2147483647, 'message' =>'Check the value!', 'tooBig' => 'Must not be greater than 2147483647.', 'tooSmall' => 'Must not be less than 0.'],
            [['empcode'],'number','min'=>0,'max'=>2147483647, 'message' =>'Check the value!', 'tooBig' => 'Must not be greater than 2147483647.', 'tooSmall' => 'Must not be less than 0.'],
            [['doj', 'dor', 'dob', 'doresign', 'dope', 'superannuationdate'], 'safe', 'message' =>'Enter valid Date!'],      
            ['dor', 'compare', 'compareAttribute'=>'doj', 'operator'=>'>', 'type' => 'date', 'message' => 'Check the Date!'],
            ['dob', 'compare', 'compareValue'=>'1996-01-01', 'operator'=>'<', 'message' => 'Check Date of Birth!'],            
            ['dor', 'compare', 'compareAttribute'=>'doj', 'operator'=>'>', 'type' => 'date', 'message' => 'Date of Renewal cannot be less than Date of Joining!'],
            ['salary', 'compare', 'compareValue'=>1, 'operator'=>'>', 'type' => 'number', 'message' => 'Check the salary!'],
            ['salary', 'compare', 'compareValue'=>2147483647 , 'operator'=>'<=', 'type' => 'number', 'message' => 'Check the salary!'],
            [['salary'],'number','min'=>1,'max'=>2147483647, 'message' =>'Check the value!', 'tooBig' => 'Must not be greater than 2147483647.', 'tooSmall' => 'Must not be less than 0.'],
            ['doresign', 'compare', 'compareAttribute'=>'doj', 'operator'=>'>', 'type' => 'date', 'message' => 'Date of Resign cannot be less than Date of Joining!'],
            ['cdacexperience', 'compare', 'compareAttribute'=>'totalexperience', 'operator'=>'<=', 'type' => 'number', 'message' => 'C-DAC experience should be greater than or equal to total experience!!'],
            [['sessionid'], 'string', 'max' => 255, 'message' =>'Sorry, some error occured!'],
            [['phone'], 'string', 'max' => 50, 'message' =>'Enter valid Number!', 'tooLong' => 'Must not exceeds 50 character'],
            [['phone'], 'match', 'pattern' => '/^[0-9]+$/i', 'message' =>'Enter valid Number!'],
            [['name', 'email'], 'string', 'max' => 500, 'message' =>'Enter valid characters!', 'tooLong' => 'Must not exceeds 500 character'],
            [['technologyid', 'qualification'], 'string', 'max' => 1000, 'message' =>'Please select Properly!', 'tooLong' => 'Must not exceeds 1000 character'],            
            [['emptype'], 'string', 'max' => 100, 'message' =>'Please Select!', 'tooLong' => 'Must not exceeds 100 character'],
            [['gender', 'stafftype', 'grade', 'payband'], 'string', 'max' => 50, 'message' =>'Sorry, some error occured!'],
            [['scale'], 'string', 'max' => 100, 'message' =>'Maximum length of characters exceeded'],
            [['category'], 'string', 'max' => 200, 'message' =>'Maximum length of characters exceeded'],
            /*['cdacdeptid', 'required', 'when' => function($model) {
                return $model->designation->designation != 'Director';
            }]*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activeuser' => 'Activeuser',
            'deleted' => 'Deleted',
            'id' => 'ID',
            'empcode' =>'Empcode',            
            'sessionid' => 'Sessionid',
            'updatedon' => 'Updatedon',
            'name' => 'Name',
            'doj' => 'Doj',
            'dor' => 'Dor',
            'dob' => 'Dob',
            'designationid' => 'Designationid',
            'email' => 'Email',
            'phone' => 'Phone',
            'salary' => 'Salary',
            'technologyid' => 'Technologyid',
            'qualification' => 'Qualification',
            'totalexperience' => 'Totalexperience',
            'cdacexperience' => 'Cdacexperience',
            'doresign' => 'Doresign',
            'coi' => 'Chief Investigator',
            'emptype' =>'emptype',
            'cdacdeptid' => 'cdacdeptid',
            'gradepay' => 'gradepay',
            'gender' => 'gender', 
            'stafftype' => 'stafftype', 
            'grade' => 'grade', 
            'payband' => 'payband', 
            'scale' => 'scale', 
            'dope' => 'dope', 
            'superannuationdate' => 'superannuationdate', 
            'category' => 'category'
        ];
    }
    
    public function getDesignation()
    {
        return $this->hasOne(Designations::className(), ['id' => 'designationid'])->where(['designations.deleted' => 0]);                
    }
    
    public function getProjecttechnology()
    {
        $content = explode(',', $this->technologyid);         
        $final='';        
        for($i=0;$i<count($content);$i++) 
        {
            $customer = Projecttechnology::findOne($content[$i]);            
            if($final==''){
                $final = $customer->technology ;
            }
            else {
                $final = $final .',    '. $customer->technology ;
            }
        }         
        return $final;
    }
    
     public function getQualifications()
    {
        $content = explode(',', $this->qualification);       
                
        $final='';        
        for($i=0;$i<count($content);$i++) 
        {
            $customer = Qualification::findOne($content[$i]);            
                        
            if($final==''){
                $final = $customer->qualification ;
            }
            else {
                $final = $final .',    '. $customer->qualification ;
            }
        }              
        
        return $final;
    }
    
    public function getMapping()
    {
        return $this->hasOne(Manpower::className(), ['id' => 'id'])->select('*')->where(['deleted'=>0])->with('manpowersalary');
    }
    
    public function getManpowermapping()
    {
        return $this->hasMany(Manpowermapping::className(), ['manpowerid' => 'id'])->where(['deleted'=>0])->orderby('workstartdate')->with('orderdetail');
    }    
    
    public function getManpowersalary()
    {
        return $this->hasOne(Manpowermapping::className(), ['manpowerid' => 'id'])->where(['deleted'=>0])->with('orderdetail');
    }
    
    public function getManpower()
    {
        return $this->hasOne(Manpowermapping::className(), ['id' => 'id'])->where(['deleted'=>0])->with('orderdetail');
    }
    
     public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
    
    public function getCoi()
    {
        return $this->hasOne(Manpower::className(), ['id' => 'coi'])->select('*')->where(['deleted'=>0]);
    }
    
    public function getManpmapp()
    {
        return $this->hasMany(Manpowermapping::className(), ['manpowerid' => 'id'])->where(['deleted'=>0])->with('tskassgn');
    }
    
    public function getTaskassign()
    {
        return $this->hasMany(Taskassign::className(), ['manpowerid' => 'id'])->where(['<', 'progress', 100])->with('taskmgr');
    }
    
    public function getCdacdept()
    {
        return $this->hasOne(Cdacdept::className(), ['id' => 'cdacdeptid']);
    }
}
