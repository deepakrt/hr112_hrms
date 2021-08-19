<?php

namespace app\modules\manageproject\models;

use Yii;

/**
 * This is the model class for table "pmis_manpowermapping".
 *
 * @property integer $id
 * @property integer $orderid
 * @property integer $manpowerid
 * @property double $mandays
 * @property string $workstartdate
 * @property integer $salary
 * @property string $sactionpost
 * @property integer $activeuser
 * @property integer $deleted
 * @property string $sessionid
 * @property string $updatedon
 */
class Manpowermapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pmis_manpowermapping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['orderid', 'manpowerid', 'mandays', 'salary', 'sactionpost', 'activeuser', 'sessionid'], 'required'],
            [['orderid', 'manpowerid', 'salary', 'activeuser', 'deleted'], 'integer'],
            [['mandays'], 'number'],
            [['workstartdate', 'updatedon'], 'safe'],
            [['sactionpost'], 'string', 'max' => 200],
            [['sessionid'], 'string', 'max' => 255],
            [['orderid', 'manpowerid'], 'unique', 'targetAttribute' => ['orderid', 'manpowerid'], 'message' => 'The combination of Orderid and Manpowerid has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => 'Orderid',
            'manpowerid' => 'Employee Name',
            'mandays' => 'Mandays',
            'workstartdate' => 'Workstartdate',
            'salary' => 'Salary Percentage',
            'sactionpost' => 'Sactioned Post',
            'activeuser' => 'Activeuser',
            'deleted' => 'Deleted',
            'sessionid' => 'Sessionid',
            'updatedon' => 'Updatedon',
        ];
    }
    
    public function getProject()
    {
        return $this->hasOne(Projectdetail::className(), ['orderid' => 'orderid'])->select('*')->with('orderdetail');
    }
}
