<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "policies_guidelines".
 *
 * @property int $id
 * @property int $title
 * @property int $description
 * @property int $is_active
 * @property string $sdate
 * @property int $created_by
 */
class PoliciesGuidelines extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'policies_guidelines';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'is_active', 'sdate', 'created_by'], 'required'],            
             [['title', 'document','description', 'is_active', 'created_by'], 'string'],
            [['sdate','valid_upto'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'is_active' => 'Is Active',
            'sdate' => 'Sdate',
            'created_by' => 'Created By',
        ];
    }
}
