<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grievance_type".
 *
 * @property int $id
 * @property int $title
 * @property int $description
 * @property string $is_active
 */
class GrievanceType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grievance_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'is_active'], 'required'],
            [['title', 'description'], 'string'],
            [['is_active'], 'string', 'max' => 240],
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
        ];
    }
}
