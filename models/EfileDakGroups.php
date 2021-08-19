<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_groups".
 *
 * @property integer $dak_group_id
 * @property integer $file_id
 * @property string $group_name
 * @property string $created_by
 * @property string $created_date
 * @property string $is_active
 */
class EfileDakGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'group_name', 'created_by', 'created_date'], 'required'],
            [['file_id'], 'integer'],
            [['created_date'], 'safe'],
            [['is_active'], 'string'],
            [['group_name', 'created_by'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dak_group_id' => 'Dak Group ID',
            'file_id' => 'File ID',
            'group_name' => 'Group Name',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
        ];
    }
}
