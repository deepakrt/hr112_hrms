<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_project_list".
 *
 * @property integer $project_id
 * @property string $project_name
 * @property string $short_name
 * @property string $project_type
 * @property string $description
 * @property string $work_scope
 * @property string $address
 * @property string $contact_person
 * @property string $contact_no
 * @property string $alternate_contact_no
 * @property string $project_cost
 * @property string $start_date
 * @property string $end_date
 * @property integer $num_working_days
 * @property integer $duration_month
 * @property integer $num_manpower
 * @property string $work_in_phase1
 * @property string $work_in_phase2
 * @property string $work_in_phase3
 * @property string $work_in_phase4
 * @property string $work_in_phase5
 * @property string $technology_used
 * @property string $approval_doc
 * @property string $updated_by
 * @property string $last_updated_on
 * @property string $created_on
 * @property string $status
 * @property string $is_active
 */
class ProjectList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pr_project_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'project_type', 'description', 'work_scope', 'address', 'contact_person', 'contact_no', 'project_cost', 'start_date', 'end_date', 'num_working_days', 'duration_month', 'num_manpower', 'work_in_phase1', 'technology_used', 'manager_dept', 'manager_emp_id', 'updated_by', 'created_on', 'status'], 'required'],
            [['project_type', 'description', 'work_scope', 'work_in_phase1', 'work_in_phase2', 'work_in_phase3', 'work_in_phase4', 'work_in_phase5', 'technology_used', 'status', 'is_active', 'manager_emp_id' ], 'string'],
            [['start_date', 'end_date', 'last_updated_on', 'created_on'], 'safe'],
            [['num_working_days', 'duration_month', 'num_manpower', 'manager_dept'], 'integer'],
            [['project_name', 'short_name', 'address', 'contact_person', 'approval_doc'], 'string', 'max' => 255],
            [['contact_no', 'alternate_contact_no'], 'string', 'max' => 15],
            [['project_cost', 'updated_by'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'project_name' => 'Project Name',
            'short_name' => 'Short Name',
            'project_type' => 'Project Type',
            'description' => 'Project Description',
            'work_scope' => 'Work Scope',
            'address' => 'Office Address',
            'contact_person' => 'Contact Person',
            'contact_no' => 'Contact No.',
            'alternate_contact_no' => 'Alternate Contact',
            'project_cost' => 'Project Cost',
            'start_date' => 'Project Start Date',
            'end_date' => 'Project End Date',
            'num_working_days' => 'No. of Working Days',
            'duration_month' => 'Duration In Months',
            'num_manpower' => 'ManPower Required',
            'work_in_phase1' => 'Work In Phase 1',
            'work_in_phase2' => 'Work In Phase 2',
            'work_in_phase3' => 'Work In Phase 3',
            'work_in_phase4' => 'Work In Phase 4',
            'work_in_phase5' => 'Work In Phase 5',
            'technology_used' => 'Technologies Used',
            'manager_dept' => 'Select Department',
            'manager_emp_id' => 'Select Project Manager',
            'approval_doc' => 'Project File (Only .pdf)',
            'updated_by' => 'Updated By',
            'last_updated_on' => 'Last Updated',
            'created_on' => 'Created On',
            'status' => 'Project Status',
            'is_active' => 'Project Active?',
        ];
    }
}