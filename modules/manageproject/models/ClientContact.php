<?php

namespace app\modules\manageproject\models;
use app\modules\manageproject\models\ClientContact;
use app\modules\manageproject\models\ClientDetail;

use Yii;

/**
 * This is the model class for table "client_contact".
 *
 * @property integer $id
 * @property integer $clientid
 * @property string $name
 * @property integer $phone
 * @property integer $mobile
 * @property string $email
 * @property string $remarks
 * @property string $updatedon
 * @property integer $userid
 */
class ClientContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pmis_client_contact}}';
       
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['clientid', 'cdacdeptid', 'name', 'userid', 'sessionid'], 'required', 'message' =>'Required!'],
            [['id', 'userid'], 'integer', 'integerOnly'=>true, 'message' =>''],
            [['clientid'], 'integer', 'integerOnly'=>true, 'message' =>'Select the Client/Department!'],
            [['phone', 'mobile'], 'string', 'max'=>50, 'message' =>'Enter valid Phone Number!'],
            [['updatedon'], 'safe', 'message' =>''],
            [['name'], 'string', 'max' => 50, 'message' =>'Enter valid Characters!', 'tooLong' => 'Must not exceeds 50 character'],
            [['name'], 'filter', 'filter'=>'trim', 'message' =>'No Special Characters allowed!'],
            [['name'],'match' ,'pattern'=>'/^[a-zA-Z,()_\-\s]+$/i', 'message'=> 'Username can contain only characters and hyphens(-).'],
            //[['name'],'isNameOnly'],
            //[['name'], 'match', 'pattern' => '/^[a-zA-Z0-9\s]+$/i'],
            //[['mobile'],'integer','min' => 10, 'message'=> 'Enter a valid mobile number.'],            
            //[['phone'], 'match', 'pattern' => '^\\d{3}[-\\.\\s]\\d{3}[-\\.\\s]\\d{4}$'],            
            //[['mobile'], 'match', 'pattern' => '^\\d{10}$'],            
            [['email'], 'string', 'max' => 225, 'message' =>'Enter valid eMail address!', 'tooLong' => 'Must not exceeds 225 character'],
            [['email'],'email', 'message' =>'Enter valid eMail address!'],
            [['remarks'], 'string', 'max' => 300, 'message' =>'Only charcters are allowed!', 'tooLong' => 'Must not exceeds 300 character'],
            [['remarks'],'match' ,'pattern'=>'/^[a-zA-Z0-9,()_\-\s]+$/i', 'message'=> 'Only alphanumeric characters and hyphens(-) are permitted.'],
            [['sessionid'], 'string', 'max' => 255, 'message' =>''],
            [['sessionid'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/i', 'message' =>''],
        ];
    }

   // public function isNameOnly($attribute)
    //{
      //  if (!preg_match('/^[a-zA-Z0-9\s]+$/i', $this->$attribute)) {
        //    $this->addError($attribute, 'must contain only characters and numbers.');
        //}
    //}
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'clientid' => 'clientid',
            'name' => 'Name',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'remarks' => 'Remarks',
            'updatedon' => '',
            'userid' => 'Userid',
            'sessionid' => 'Sessionid',
            'cdacdeptid' => 'cdacdeptid',
        ];
    }
    
    public function getDeptName()
    {
        return $this->hasOne(ClientDetail::className(), ['id' => 'clientid']);
                
    }
    
  

}
