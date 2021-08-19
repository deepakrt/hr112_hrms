<?php

namespace app\models;
use Yii;
class Group extends \yii\db\ActiveRecord
{
    public $CLASSIFICATION_CODE;
    public $CLASSIFICATION_NAME;

    public static function tableName()
    {
        return 'store_classification_master';
    }

    public function rules()
    {
        return [
            [['CLASSIFICATION_NAME'], 'required'],
            [['CLASSIFICATION_NAME'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CLASSIFICATION_NAME' => 'Group Name',
        ];
    }
}
