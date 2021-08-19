<?php

namespace app\models;

use Yii;

class EfileDak extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['file_type', 'subject', 'priority', 'is_confidential', 'remarks', 'summary', 'sent_for_scan', 'status', 'is_active'], 'string'],
            [['reference_num', 'reference_date', 'subject', 'file_category_id', 'action_type', 'access_level', 'priority', 'is_confidential', 'sent_for_scan', 'status', 'emp_code', 'created_date'], 'required'],
            [['reference_date', 'created_date', 'last_updated'], 'safe'],
            [['reference_num', 'meta_keywords'], 'string', 'max' => 255],
            [['action_type', 'access_level', 'emp_code'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'rec_id' => 'Rec ID',
            'file_type' => 'R: Receive, N:New',
            'reference_num' => 'संदर्भ संख्या / Reference Num',
            'reference_date' => 'संदर्भ दिनांक / Reference Date',
            'subject' => 'विषय / Subject',
            'file_category_id' => 'File Category ID',
            'file_project_id' => 'परियोजना का नाम /Project Name',
            'action_type' => 'अग्रेषित / Forward For',
            'access_level' => 'अनुमति / Access Level',
            'priority' => 'प्राथमिकता / Priority',
            'is_confidential' => 'गोपनीय है / Is Confidential',
            'meta_keywords' => 'मेटा कीवर्ड / Meta Keywords',
            'remarks' => 'टिप्पणी (यदि कोई हो) / Remarks (if any)',
            'summary' => 'सारांश (यदि कोई हो)  / Summary (if any)',
            'sent_for_scan' => 'Sent For Scan',
            'status' => 'Status',
            'emp_code' => 'Emp Code',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'is_active' => 'Is Active',
        ];
    }
}
