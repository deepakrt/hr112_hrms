<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_project_tasks".
 *
 * @property int $task_id
 * @property int|null $parent_task_id
 * @property int $project_id
 * @property string $task_name
 * @property string $task_description
 * @property int $assigned_to
 * @property int $assigned_to_name
 * @property int $assigned_by
 * @property string $priority
 * @property string $task_type
 * @property string $start_date
 * @property string $task_end_date_fla
 * @property string|null $task_end_date_emp
 * @property int $progress
 * @property string|null $remarks
 * @property string $state
 * @property string $created_on
 * @property string $updated_on
 * @property string $is_active
 */
class PrProjectTasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	public $dept_id;
    public static function tableName()
    {
        return 'pr_project_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_task_id', 'project_id', 'assigned_to', 'assigned_by', 'progress'], 'integer'],
            [['project_id', 'task_name', 'task_description', 'assigned_to', 'assigned_by', 'priority', 'task_type', 'start_date', 'task_end_date_fla', 'progress', 'created_on','assigned_to_name'], 'required'],
            [['priority', 'task_type', 'remarks', 'state', 'is_active'], 'string'],
            [['start_date', 'task_end_date_fla', 'task_end_date_emp', 'created_on', 'updated_on'], 'safe'],
            [['task_name'], 'string', 'max' => 200],
            [['task_description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'parent_task_id' => 'Parent Task',
            'project_id' => 'Project',
            'task_name' => 'Task',
            'task_description' => 'Task Description',
            'assigned_to' => 'Assigned To',
            'assigned_to_name' => 'Assigned To Name',
            'assigned_by' => 'Assigned By',
            'priority' => 'Priority',
            'task_type' => 'Task Type',
            'start_date' => 'Start Date',
            'task_end_date_fla' => 'End Date',
            'task_end_date_emp' => 'Task End Date',
            'progress' => 'Progress',
            'remarks' => 'Remarks',
            'state' => 'State',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_active' => 'Is Active',
        ];
    }
}
