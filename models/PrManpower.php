<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pmis_manpower".
 *
 * @property int $id
 * @property int $project_id
 * @property int $emp_id
 * @property string $emp_name
 * @property int $salary
 * @property string $working_as
 * @property string $working_on
 * @property int $added_by
 * @property string $added_date
 * @property string $is_active
 */
class PrManpower extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_manpower';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'emp_id', 'emp_name', 'salary', 'working_as', 'working_on', 'added_by'], 'required'],
            [['project_id', 'emp_id', 'salary', 'added_by'], 'integer'],
            [['added_date'], 'safe'],
            [['is_active'], 'string'],
            [['emp_name', 'working_as', 'working_on'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'emp_id' => 'Emp ID',
            'emp_name' => 'Emp Name',
            'salary' => 'Salary',
            'working_as' => 'Working As',
            'working_on' => 'Working On',
            'added_by' => 'Added By',
            'added_date' => 'Added Date',
            'is_active' => 'Is Active',
        ];
    }
}
