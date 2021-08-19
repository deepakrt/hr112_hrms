<?php

namespace app\modules\manageproject\models;
use frontend\models\Ordermaster;
use frontend\models\Projectdetail;

use Yii;

/**
 * This is the model class for table "auditmaster".
 *
 * @property integer $id
 * @property integer $projectid
 * @property string $audittype
 * @property string $startdate
 * @property string $auditagency
 * @property string $auditreport
 * @property string $reportdate
 * @property string $status
 * @property string $remarks
 * @property integer $activeuser
 * @property integer $deleted
 * @property string $sessionid
 * @property string $updatedon
 */
class Auditmaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auditmaster}}';
        //return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'audittype', 'startdate', 'auditagency', 'status', 'activeuser', 'sessionid'], 'required', 'message' =>'Required!'],
            [['activeuser', 'deleted'], 'integer', 'message' =>''],
            [['orderid'], 'safe', 'message' =>'Select the Project!'],
            [['startdate', 'reportdate', 'updatedon'], 'safe', 'message' =>'Enter valid date!'],
            ['reportdate', 'compare', 'compareAttribute'=>'startdate', 'operator'=>'>', 'type' => 'date', 'message' => 'Report cannot be received before start of Audit!'],
            [['audittype', 'status'], 'string', 'max' => 200, 'message' =>'Enter valid characters!', 'tooLong' => 'Length must not exceeds 200 character' ],
            [['audittype', 'status'], 'match', 'pattern' => '/^[a-zA-Z0-9\s]+$/i', 'message' =>'No Special Character allowed!'],
            [['auditagency', 'auditreport', 'remarks'], 'string', 'max' => 2000, 'message' =>'Enter valid characters!', 'tooLong' => 'Length must not exceeds 2000 character'],
            [['auditagency', 'auditreport', 'remarks'], 'match', 'pattern' => '/^[a-zA-Z0-9\s]+$/i', 'message' =>'No Special Character allowed!'],
            [['sessionid'], 'string', 'max' => 255, 'message' =>''],            
            [['id'], 'unique', 'message' =>''],            
            [['sessionid'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/i', 'message' =>''],
            [['activeuser'], 'integer', 'message' =>''],
            //['startdate', 'compare', 'compareAttribute'=>'compareDateRange', 'type' => 'date',]
            //['startdate', 'compare',  'compareValue'=>Projects::find()->select(['projectstartdate'])->where(['orderid'=>Yii::$app->projectcls->SelectAudit($this->id)->orderid])->all()[0], 'operator'=>'>=', 'type' => 'date',  'message' => 'Check the Dateeeeeee!'],
            //[['orderid', 'audittype', 'startdate'], 'unique', 'targetAttribute' => ['orderid', 'audittype', 'startdate'], 'message' => 'The combination of Order, Audit Type and Start Date has already been taken.'],
            //[['orderid'], 'unique', 'message' => 'Already Exists!']
          
        ];
    }

    /*public function compareDateRange($attribute,$params) {
        if(!empty($this->attributes['startdate'])) {
            if(strtotime(Yii::$app->projectcls->Projectwithorder($this->attributes['orderid'])->projectstartdate) <= strtotime($this->attributes['startdate'])) {
                $this->addError($attribute,'The expired date can not be less/before posted date.');
            }
        }
 
    }
     
    
    public function getStartTimestamp($attribute){
        return (Projects::find()->select(['projectstartdate'])->where(['orderid'=>Yii::$app->projectcls->SelectAudit($this->id)->orderid])->asArray()->column());
    }
* 
     */
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => 'Orderid',
            'audittype' => 'Audittype',
            'startdate' => 'Startdate',
            'auditagency' => 'Auditagency',
            'auditreport' => 'Auditreport',
            'reportdate' => 'Reportdate',
            'status' => 'Status',
            'remarks' => 'Remarks',
            'activeuser' => 'Activeuser',
            'deleted' => 'Deleted',
            'sessionid' => 'Sessionid',
            'updatedon' => 'Updatedon',
        ];
    }
    
    //connect projects, ordermaster and client detail tables
    public function getClientdetail()
    {
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid'])->where(['clientdetail.deleted' => 0]);
    }
    
    public function getOrderdetail()
    {
        return $this->hasOne(Ordermaster::className(), ['id' => 'orderid'])->with(['clientdetail']);
    }
    
    public function getProject()
    {
        return $this->hasOne(Projectdetail::className(), ['id' => 'id'])->select('*')->with('orderdetail');
    }
}
