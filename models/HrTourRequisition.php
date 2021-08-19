<?php
namespace app\models;
use Yii;
class HrTourRequisition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_tour_requisition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'dept_id', 'project_name', 'tour_type', 'tour_location', 'advance_amount', 'sanctioned_adv_amount', 'start_date', 'end_date', 'purpose', 'sanctioned_by', 'sanctioned_on', 'status', 'last_updated', 'submitted_on'], 'required'],
            [['e_id', 'dept_id', 'advance_amount', 'sanctioned_adv_amount', 'sanctioned_by'], 'integer'],
            [['tour_type', 'advance_required', 'status', 'is_active'], 'string'],
            [['start_date', 'end_date', 'sanctioned_on', 'last_updated', 'submitted_on'], 'safe'],
            [['project_name', 'purpose'], 'string', 'max' => 255],
            [['tour_location'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'e_id' => 'E ID',
            'dept_id' => 'Dept ID',
            'project_name' => 'Project Name',
            'tour_type' => 'Tour Type',
            'tour_location' => 'Tour Location',
            'advance_required' => 'Advance Required',
            'advance_amount' => 'Advance Amount',
            'sanctioned_adv_amount' => 'Sanctioned Adv Amount',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'purpose' => 'Purpose',
            'sanctioned_by' => 'Sanctioned By',
            'sanctioned_on' => 'Sanctioned On',
            'status' => 'Status',
            'last_updated' => 'Last Updated',
            'submitted_on' => 'Submitted On',
            'is_active' => 'Is Active',
        ];
    }

}
