<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "policy_master".
 *
 * @property int $id
 * @property string $police_name
 * @property string $is_active
 * @property string $created_by
 * @property string $sdate
 */
class PolicyMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'policy_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['police_name', 'is_active', 'created_by', 'sdate'], 'required'],
            [['police_name'], 'string'],
            [['sdate'], 'safe'],
            [['is_active', 'created_by'], 'string', 'max' => 240],
             [['policy_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'police_name' => 'Police Name',
            'is_active' => 'Is Active',
            'created_by' => 'Created By',
            'sdate' => 'Sdate',
            'policy_id' => 'Policy',
        ];
    }
}
