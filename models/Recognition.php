<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recognition".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $reco_type
 * @property string $from_department
 * @property string $from_type 1 for internal, 2 for external
 * @property string $is_active
 * @property string $created
 */
class Recognition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recognition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'reco_type', 'from_department', 'from_type', 'is_active'], 'required'],
            [['reco_type', 'from_type', 'is_active'], 'string'],
            [['created'], 'safe'],
            [['name', 'from_department'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
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
            'reco_type' => 'Recognition Type',
            'from_department' => 'Department Name',
            'from_type' => 'Recognition From',
            'is_active' => 'Is Active',
            'created' => 'Created',
        ];
    }
}
