<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fts_group_master".
 *
 * @property integer $group_id
 * @property string $group_name
 * @property string $group_description
 * @property integer $created_by
 * @property string $creation_date
 * @property string $last_modified_date
 *
 * @property FtsGroupMembers[] $ftsGroupMembers
 */
class FtsGroupMaster extends \yii\db\ActiveRecord
{
	public $departments;
	public $members;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fts_group_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'group_description', 'created_by'], 'required'],
            [['created_by'], 'integer'],
            [['creation_date', 'last_modified_date'], 'safe'],
            [['group_name', 'group_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'group_name' => 'Group Name',
            'group_description' => 'Group Description',
            'created_by' => 'Created By',
            'creation_date' => 'Creation Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFtsGroupMembers()
    {
        return $this->hasMany(FtsGroupMembers::className(), ['group_id' => 'group_id']);
    }
}
