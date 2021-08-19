<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_family_relations".
 *
 * @property integer $relation_id
 * @property string $relation_name
 * @property string $relation_desc
 * @property string $is_active
 *
 * @property EmployeeFamilyDetails[] $employeeFamilyDetails
 */
class MasterFamilyRelations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_master_family_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relation_name', 'relation_desc'], 'required'],
            [['is_active'], 'string'],
            [['relation_name'], 'string', 'max' => 100],
            [['relation_desc'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relation_id' => 'Relation ID',
            'relation_name' => 'Relation Name',
            'relation_desc' => 'Relation Desc',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFamilyDetails()
    {
        return $this->hasMany(EmployeeFamilyDetails::className(), ['relation_id' => 'relation_id']);
    }
}
