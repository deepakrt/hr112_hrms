<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_master_category".
 *
 * @property integer $file_category_id
 * @property string $name
 * @property string $is_active
 */
class EfileMasterCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_master_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'string'],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_category_id' => 'File Category ID',
            'name' => 'Name',
            'name_hindi' => 'Name Hindi',
            'is_active' => 'Is Active',
        ];
    }
}
