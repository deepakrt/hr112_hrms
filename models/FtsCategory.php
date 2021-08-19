<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fts_category".
 *
 * @property integer $fts_category_id
 * @property string $cat_name
 * @property string $is_hierarchical
 * @property string $description
 * @property string $is_active
 *
 * @property FtsDak[] $ftsDaks
 */
class FtsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fts_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name', 'is_hierarchical', 'description'], 'required'],
            [['is_hierarchical', 'is_active'], 'string'],
            [['cat_name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fts_category_id' => 'Category ID',
            'cat_name' => 'Cat Name',
            'is_hierarchical' => 'Is Hierarchical',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFtsDaks()
    {
        return $this->hasMany(FtsDak::className(), ['category' => 'fts_category_id']);
    }
}
