<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_project_list".
 *
 * @property int $project_id Project ID
 * @property int $orderid
 * @property string $projectrefno
 * @property string|null $proposal_no
 * @property string|null $proposal_submission_date
 * @property string|null $order_num
 * @property string|null $project_name Project Name
 * @property string|null $short_name Short Name
 * @property string $project_type Project Type
 * @property string $objectives
 * @property string $finaloutcome
 * @property string|null $completionreport
 * @property string|null $appreciationcert
 * @property string|null $actualcompletiondate
 * @property int|null $reference_projectid
 * @property string $filenumber
 * @property string|null $description Project Description
 * @property string|null $address Office Address
 * @property string|null $contact_person Contact Person
 * @property string|null $contact_no Contact No.
 * @property string|null $alternate_contact_no Alternate Contact
 * @property string|null $project_cost Project Cost
 * @property string $start_date Project Start Date
 * @property string $end_date Project End Date
 * @property int|null $manager_dept Department
 * @property string|null $approval_doc Approval File (Only .pdf)
 * @property string $updated_by Updated By
 * @property string|null $last_updated_on Last Updated
 * @property string $created_on Created On
 * @property string|null $status Status
 * @property string $is_active Is Active
 *
 * @property StoreMaterialPurchaseRequest[] $storeMaterialPurchaseRequests
 */
class ProjectList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_project_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderid', 'project_name', 'projectrefno', 'project_type', 'objectives', 'finaloutcome', 'filenumber', 'start_date', 'end_date', 'proposal_submission_date', 'updated_by', 'proposal_no', 'project_cost', 'manager_dept', 'contact_person', 'funding_agency', 'description'], 'required'],
            [['orderid', 'reference_projectid', 'manager_dept'], 'integer'],
            [['project_type', 'objectives', 'finaloutcome', 'description', 'status', 'is_active'], 'string'],
            [['actualcompletiondate', 'start_date', 'end_date', 'last_updated_on', 'created_on'], 'safe'],
            [['projectrefno', 'proposal_no', 'proposal_submission_date', 'order_num'], 'string', 'max' => 100],
            [['funding_agency'], 'string', 'max' => 300],
            [['project_name', 'short_name', 'address', 'contact_person', 'approval_doc'], 'string', 'max' => 255],
            [['completionreport', 'appreciationcert'], 'string', 'max' => 1],
            [['filenumber', 'project_cost', 'updated_by'], 'string', 'max' => 50],
            [['contact_no', 'alternate_contact_no'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'orderid' => 'Orderid',
            'projectrefno' => 'Project Registration Number',
            'proposal_no' => 'Proposal Number',
            'proposal_submission_date' => 'Proposal Submission Date',
			'order_num' => 'Work Order / Admin Approval Number',
			'funding_agency' => 'Name of Funding Agency',
            'project_name' => 'Project Name',
            'short_name' => 'Short Name',
            'project_type' => 'Project Type',
            'objectives' => 'Objectives',
            'finaloutcome' => 'Final Outcome',
            'completionreport' => 'Completion Report',
            'appreciationcert' => 'Appreciation Cert',
            'actualcompletiondate' => 'Actual Completion Date',
            'reference_projectid' => 'Reference Project',
            'filenumber' => 'File Number',
            'description' => 'Description',
            'address' => 'Address of Funding Agency',
            'contact_person' => 'Contact Person',
            'contact_no' => 'Contact No',
            'alternate_contact_no' => 'Alternate Contact No',
            'project_cost' => 'Project Cost',
            'start_date' => 'Project Start Date',
            'end_date' => 'Project End Date',
            'manager_dept' => 'Department',
            'approval_doc' => 'Approval Doc',
            'updated_by' => 'Updated By',
            'last_updated_on' => 'Last Updated On',
            'created_on' => 'Created On',
            'status' => 'Status',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[StoreMaterialPurchaseRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreMaterialPurchaseRequests()
    {
        return $this->hasMany(StoreMaterialPurchaseRequest::className(), ['project_id' => 'project_id']);
    }
}
