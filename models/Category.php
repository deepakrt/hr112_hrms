<?php

namespace app\models;
use Yii;
class Category extends \yii\db\ActiveRecord
{
    public $ITEM_CAT_CODE;
    public $ITEM_CAT_NAME;

    public static function tableName()
    {
        return 'store_item_cat_master';
    }

    public function rules()
    {
        return [
            [['ITEM_CAT_NAME'], 'required'],
            [['ITEM_CAT_NAME'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ITEM_CAT_NAME' => 'Category Name',
        ];
    }
}
