<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reward_master".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $created_by
 * @property string $is_active
 * @property string $created_date
 * @property string $modified_date
 */
class RewardMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'created_by','reward_type_id','reward_sub_cat', 'is_active', 'created_date', 'modified_date'], 'required'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_by' => 'Created By',
            'reward_type_id' => 'Reward Type',
            'reward_sub_cat' => 'Sub Category',
            'is_active' => 'Is Active',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
        ];
    }
}
