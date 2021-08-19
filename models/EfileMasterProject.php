<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_master_project".
 *
 * @property integer $file_project_id
 * @property string $project_name
 * @property string $added_by
 * @property string $added_on
 * @property string $is_active
 */
class EfileMasterProject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_master_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'added_by', 'added_on'], 'required'],
            [['added_on'], 'safe'],
            [['is_active'], 'string'],
            [['project_name', 'added_by'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_project_id' => 'File Project ID',
            'project_name' => 'Project Name',
            'added_by' => 'Added By',
            'added_on' => 'Added On',
            'is_active' => 'Is Active',
        ];
    }
}
