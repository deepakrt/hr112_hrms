<?php

namespace app\modules\manageproject\models;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\ClientDetail;
//use app\modules\manageproject\models\Reportprojects;
use app\modules\manageproject\models\Projecttype;
use app\modules\manageproject\models\Projecttechnology;
use app\modules\manageproject\models\Projectdatabase;
use app\modules\manageproject\models\Manpower;
use app\modules\manageproject\models\Investigator;
use app\modules\manageproject\models\Partypayments;
use app\modules\manageproject\models\Capitalmaster;
use app\modules\manageproject\models\Country;
use app\modules\manageproject\models\Auditmaster;
use app\modules\manageproject\models\Capitalpurchase;
use yii\db\Query;
use yii\db\QueryBuilder ;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property integer $id
 * @property integer $activeuser
 * @property integer $orderid
 * @property string $projectrefno
 * @property integer $projecttypeid
 * @property integer $investigatorid
 * @property integer $coinvestigatorid
 * @property string $projectstartdate
 * @property string $expectedenddate
 * @property integer $milestoneid
 * @property string $objectives
 * @property integer $technologyid
 * @property integer $databaseused
 * @property integer $manpowerid
 * @property integer $finaloutcome
 * @property integer $completionreport
 * @property integer $appreciationcert
 * @property string $actualcompletiondate
 * @property integer $referenceid
 * @property integer $deleted
 * @property string $remarks
 * @property string $sessionid
 * @property string $updatedon
 */
class Projectdetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pr_project_list}}';
    }   

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'projectrefno', 'project_name', 'project_type', 'objectives', 'finaloutcome', 'filenumber', 'contact_person', 'project_cost', 'start_date', 'end_date', 'updated_by', 'created_on'], 'required'],
            [['orderid',  'reference_projectid', 'num_working_days', 'duration_month', 'num_manpower', 'manager_dept'], 'integer'],
            [['projectrefno', 'project_type', 'objectives', 'finaloutcome', 'description', 'work_scope', 'work_in_phase1', 'work_in_phase2', 'work_in_phase3', 'work_in_phase4', 'work_in_phase5', 'technology_used', 'status', 'is_active'], 'string'],
            [['actualcompletiondate', 'start_date', 'end_date', 'last_updated_on', 'created_on'], 'safe'],
            [['project_name', 'short_name', 'address', 'contact_person', 'approval_doc'], 'string', 'max' => 255],
            [['completionreport', 'appreciationcert'], 'string', 'max' => 1],
            [['filenumber', 'project_cost', 'updated_by'], 'string', 'max' => 50],
            [['contact_no', 'alternate_contact_no'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
	 public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'orderid' => 'Orderid',
            'projectrefno' => 'Project Registration Number (C-DAC(M)/xxxx/xxx/000)',
            'project_name' => 'Project Name',
            'short_name' => 'Short Name',
            'project_type' => 'Project Type',
            'objectives' => 'Objectives',
            'finaloutcome' => 'Final Outcome',
            'completionreport' => 'Completion Report',
            'appreciationcert' => 'Appreciation Cert',
            'actualcompletiondate' => 'Actual Completion Date',
            'reference_projectid' => 'Reference Project',
            'filenumber' => 'Departmental File Number',
            'description' => 'Description',
            'work_scope' => 'Work Scope',
            'address' => 'Address',
            'contact_person' => 'Contact Person',
            'contact_no' => 'Contact No',
            'alternate_contact_no' => 'Alternate Contact No',
            'project_cost' => 'Project Cost',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'num_working_days' => 'Num Working Days',
            'duration_month' => 'Duration Month',
            'num_manpower' => 'Num Manpower',
            'work_in_phase1' => 'Work In Phase1',
            'work_in_phase2' => 'Work In Phase2',
            'work_in_phase3' => 'Work In Phase3',
            'work_in_phase4' => 'Work In Phase4',
            'work_in_phase5' => 'Work In Phase5',
            'technology_used' => 'Technology Used',
            'manager_dept' => 'Manager Dept',
            'approval_doc' => 'Approval Doc',
            'updated_by' => 'Updated By',
            'last_updated_on' => 'Last Updated On',
            'created_on' => 'Created On',
            'status' => 'Status',
            'is_active' => 'Is Active',
        ];
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
    
    public function getManpower()
    {
        $content = explode(',', $this->manpowerid);         
        $final='';        
        for($i=0;$i<count($content);$i++) 
        {
            $customer = Manpower::findOne($content[$i]);            
            if($final==''){
                $final = $customer->name ;
            }
            else {
                $final = $final .',    '. $customer->name ;
            }
        }         
        return $final;
    }
    
    public function getOrdermaster()
    {
        if (isset($_SESSION['prjsession'])) {   
            return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->where(['pmis_ordermaster.deleted' => 0, 'id' => $_SESSION['prjsession']]);
        } else {
            return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->where(['pmis_ordermaster.deleted' => 0]);
        }
    }
    
    
    //connect projects, ordermaster and client detail tables
    /*public function getClientdetail()
    {
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid'])->where(['clientdetail.deleted' => 0]);
    }*/
    
    public function getOrderdetail()
    {
        return $this->hasOne(Ordermaster::className(), ['id' => 'orderid']); 
    }
    
    public function getProject()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('orderdetail');
    }
    
    // // // // // // // //
    // 
    //connect investigator with projects and investigator with manpowr tables
    public function getProjectci()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('investigator');
    }
    
    public function getInvestigator()
    {
        return $this->hasOne(Investigator::className(), ['orderid' => 'orderid'])->with(['name']);
    }
    // // // // // // //
    
    public function getProjectteam()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('investigatord');
    }
    public function getInvestigatord()
    {           
        return $this->hasOne(Investigator::className(), ['orderid' => 'orderid'])->select('*')->with(['lead']);
    }
    
    public function getManpowermapping()
    {
        $query2 = $this->hasOne(Investigator::className(),['orderid' => 'orderid'])->select('*')->all();
        
        return $this->hasMany(Manpowermapping::className(), ['orderid' => 'orderid'])
                ->select('*')
                ->where(['NOT IN', 'manpowerid', ArrayHelper::getColumn($query2, 'teamleadid')])
                ->andwhere(['NOT IN', 'manpowerid', ArrayHelper::getColumn($query2, 'coinvestigator')])
                ->andwhere(['NOT IN', 'manpowerid', ArrayHelper::getColumn($query2, 'chiefinvestigator')])
                ->with(['manpower']);
    }


    public function getProjectType()
    {
        return $this->hasOne(Projecttype::className(), ['id' => 'projecttypeid']);                
    }
    
    public function getTechnology()
    {
        return $this->hasOne(Projecttechnology::className(), ['id' => 'technologyid']);                
    }   
    
    /////////////For proposal number
    public function getProject1()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('orderdetail');
    }
    
    public function getOrderdetail1()
    {
        return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->with(['proposal']);
    }
    
    ///Cost Matrix   
    public function getProjectcost()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('ordermaster');
    }
    
    
    ///Project Matrix   
    public function getParty()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('partypayments');
    }
    
    public function getPartypayments()
    {
        return $this->hasMany(Partypayments::className(), ['projectid' => 'id'])->select('*')->with('party');
    }
    
    
    //Capital
    public function getCapitalmaster()
    {
        return $this->hasMany(Capitalmaster::className(), ['projectid' => 'id'])->select('*');
    }
    
    
    //Dashboard
    //connect projects, ordermaster and client detail tables
    /*public function getClientdetail1()
    {
        return $this->hasOne(clientdetail::className(), ['id' => 'clientid'])->with('contacts');
    }*/
    
    public function getOrderdetaild()
    {        
        return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->with(['clientdetail1']);
    }
    
    public function getProjectdashboard() ////?????
    {
        return $this->hasOne(Projectdetail::className(), ['orderid' => 'orderid'])->select('*')->with('orderdetail');
    }  
    
    public function getMappingorder()
    {
        return $this->hasOne(Manpowermapping::className(), ['id' => 'id'])->select('*')->with('orderdetail');
    }  
    // // // // // // // //
    
    public function getOrdertype()
    {
        return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->with(['projectType']);
    }
    
    /*public function getManpowers()
    {
        return $this->hasOne(manpower::className(), ['id' => 'manpowerid']);
    }
     * 
     */
    
    public function getMember()
    {
         return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->with('meetings');        
    }
    
    public function getMapmeetings()
    {
        return $this->hasOne(Manpowermapping::className(), ['id' => 'id'])->select('*')-> where(['manpowerid'=>Yii::$app->user->identity->manpowerid])->with('member');
    }
    
    public function getMilestone()
    {        
        return $this->hasOne(Milestone::className(), ['orderid' => 'orderid']);
    } 
    
    //////////////////////////////
    public function getMapmembers()
    {
        return $this->hasOne(Manpowermapping::className(), ['id' => 'id'])->select('*')->with('investigator');
    }
    public function getInvestigator1()   //////??????
    {
        return $this->hasOne(Investigator::className(), ['orderid' => 'id']);
    }
    
    public function getAudit()
    {        
        return $this->hasOne(Auditmaster::className(), ['orderid' => 'orderid']);
    } 
    
    public function getCapital()
    {        
        return $this->hasOne(Capitalmaster::className(), ['projectid' => 'id']);
    } 
    
    public function getCapitalpurchase()
    {        
        return $this->hasOne(Capitalpurchase::className(), ['projectid' => 'id']);
    } 
    
    public function getProjectall()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => ''])->select('*')->with('orderdetail');
    }
    
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryid'])->select('*');
    }
    
    public function getAuditmaster()
    {
        return $this->hasOne(Auditmaster::className(), ['orderid' => 'orderid'])->select('*');
    }
    
    public function getReminders()
    {
        return $this->hasOne(Reminders::className(), ['projectid' => 'orderid'])->select('*');
    }
    
    public function getCdacdept()
    {
        return $this->hasOne(Cdacdept::className(), ['id' => 'cdacdeptid']);
    }
}
