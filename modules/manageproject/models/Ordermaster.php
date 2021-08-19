<?php

namespace app\modules\manageproject\models;
//use app\modules\manageproject\models\ClientDetail;
use app\modules\manageproject\models\Projecttype;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Proposal;
use app\modules\manageproject\models\Investigator;
use app\modules\manageproject\models\Receiptmaster;
use app\modules\manageproject\models\Billmaster;
use app\modules\manageproject\models\Manpowermapping;

use Yii;

/**
 * This is the model class for table "ordermaster".
 *
 * @property integer $id
 * @property integer $clientid
 * @property string $orderdate
 * @property string $number
 * @property double $amount
 * @property integer $ordertype
 * @property integer $fundingagency
 * @property integer $activeuser
 * @property integer $deleted
 * @property string $sessionid
 * @property string $updatedon
 */
class Ordermaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {        
        return '{{%pmis_ordermaster}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['cdacdeptid', 'orderdate', 'projectname', 'number', 'amount', 'fundingagency', 'activeuser', 'sessionid', 'proposalsubmissiondate', 'proposalno'], 'required', 'message' =>'Required!'],
            [['activeuser', 'deleted'], 'integer', 'message' =>'Sorry, some error occured!'],
            [[ 'proposalid'], 'integer', 'message' =>'Please Select!'],
            [['orderdate', 'updatedon'], 'safe', 'message' =>'Enter valid date!'],
            [['amount'], 'number', 'message' =>'Enter valid number!'],
            [['number'], 'string', 'max' => 100, 'message' =>'Enter valid characters!', 'tooLong' => 'Must not exceeds 100 character'],
            [['projectname'], 'string', 'max' => 2000, 'message' =>'Enter valid characters!', 'tooLong' => 'Must not exceeds 2000 character'],
            [['sessionid'], 'string', 'max' => 255, 'message' =>'Sorry, some error occured!'],
            [['fundingagency'], 'string', 'max' => 300, 'message' =>'Enter valid characters!', 'tooLong' => 'Must not exceeds 300 character'],
            [['fundingagency', 'projectname'],'match', 'pattern' => '/^[a-zA-Z0-9\-\,\s]+$/i', 'message' =>'Special Characters are not allowed!'],
            [['number'],'match', 'pattern' => '/^[a-zA-Z0-9\:\/()\-\s]+$/i', 'message' =>'Special Characters are not allowed!'],
            //['proposalid', 'in', 'range' => Ordermaster::find()->select('proposalid')->asArray()->column(),'message' =>'Work Order against this proposal already exists!'],
            //['orderdate', 'compare', 'compareValue'=>$this->proposalid?Yii::$app->projectcls->SelectProposalId($this->proposalid)[0]->submissiondate:'', 'operator'=>'>', 'type' => 'date',  'message' => 'An Order can be placed only after submission of proposal. Check Proposal submission date!'],
            //['orderdate','custom_function_validation'],
            [['number'], 'unique', 'message' =>'Already Exists!'],
            [['number'], 'unique', 'message' =>'Already Exists!'],
            [['proposalid'], 'unique', 'message' =>'Already Exists!'],
        ];
    }
    
    public function custom_function_validation($attribute){   
        if(strtotime($this->orderdate) < strtotime(Yii::$app->projectcls->Clientdetails($this->clientid)->requestDate)){ 
            $this->addError($attribute,'Check Date!');                
        }        
    }  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clientid' => 'Select the Client / Funding Agency',
            'orderdate' => 'Orderdate',
            'number' => 'Work Order / Admin Approval Number',
            'amount' => 'Approved / Sacntioned Amount (in Rs. without Tax)',
            
            'fundingagency' => 'Name of Funding Agency',
            'activeuser' => 'Activeuser',
            'deleted' => 'Deleted',
            'sessionid' => 'Sessionid',
            'updatedon' => 'Updatedon',
            'projectname' => 'Name of Project',
            'proposalid' => 'Select the Proposal',
            'cdacdeptid' => 'cdacdeptid',
            'proposalno' => 'Proposal Number (C-D\AC(M)/STD/xxxx/xxxx/xxx)',
            'proposalsubmissiondate' => 'Proposal Submission Date'
        ];
    }
    
    /*public function getClientDept()
    {       
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid'])->where(['pmis_clientdetail.deleted' => 0]);
    } */ 
    
    
    
    public function getProjects()
    {       
        return $this->hasOne(Projectdetail::className(), ['Orderid' => 'id'])->where(['pmis_projectdetail.deleted' => 0]);
    }
    
    /*public function getClientdetail()
    {
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid'])->where(['deleted' => 0]);
    }*/
    
    public function getInvestigator()
    {
        return $this->hasOne(Investigator::className(), ['orderid' => 'id'])->with(['lead']);
    }
    
    public function getCi()
    {
        return $this->hasOne(Investigator::className(), ['chiefinvestigator' => 'id'])->select('*')->with('manpower');
    }
    
    /*public function getMeetings()
    {
        return $this->hasOne(Postmeetings::className(), ['clientid' => 'clientid']);
    }*/
    
    public function getMeetingclient()
    {
        return $this->hasOne(Premeetings::className(), ['projectorderid' => 'id']);
    }
    
    /*public function getProposal()
    {
        return $this->hasOne(Proposal::className(), ['clientid' => 'clientid']);
    }*/
    
    public function getProposals()
    {
        return $this->hasOne(Proposal::className(), ['id' => 'proposalid']);
    }
    
    public function getReceiptmaster()
    {
        return $this->hasMany(Receiptmaster::className(), ['orderid' => 'id']);
    }
    
    public function getBill()
    {
        return $this->hasMany(Billmaster::className(), ['orderid' => 'id'])->with('payment');
    }   
    
    /*public function getClientdetail1()
    {
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid'])->with('contacts');
    }*/
    
    public function getManpowermapping()
    {
        return $this->hasOne(Manpowermapping::className(), ['orderid' => 'orderid'])->where(['deleted' => 0]);
    }
    
    public function getTeamlead()
    {
        return $this->hasOne(Manpower::className(), ['id' => 'manpowerid']);
    }
}
