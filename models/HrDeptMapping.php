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
class HrDeptMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_dept_mapping';
    }

    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['file_id', 'group_name', 'created_by', 'created_date'], 'required'],
//            [['file_id'], 'integer'],
//            [['created_date'], 'safe'],
//            [['is_active'], 'string'],
//            [['group_name', 'created_by'], 'string', 'max' => 200]
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dept_map_id' => 'dept_map_id',
            'employee_code' => 'employee_code',
            'dept_id' => 'dept_id',
            'effected_from' => 'effected_from',
            'effected_to' => 'effected_to',
            'created_date' => 'created_date',
            'updated_by' => 'updated_by',
            'last_updated' => 'last_updated',
            'is_active' => 'is_active',
        ];
    }
}
