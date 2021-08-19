<?php
namespace app\models;
use Yii;

class EfileDakTemp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'efile_dak_temp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_code'], 'required'],
            [['rec_id', 'file_category_id', 'file_project_id', 'action_type', 'file_id'], 'integer'],
            [['reference_date'], 'safe'],
            [['subject', 'remarks', 'summary', 'note_subject', 'note_comment', 'file_remarks'], 'string'],
            [['temp_id', 'employee_code'], 'string', 'max' => 20],
            [['file_type', 'initiate_type', 'reference_num', 'access_level', 'priority', 'is_confidential', 'meta_keywords'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'temp_id' => 'Temp ID',
            'file_id' => 'File ID',
            'employee_code' => 'Employee Code',
            'rec_id' => 'Rec ID',
            'file_type' => 'File Type',
            'initiate_type' => 'Initiate Type',
            'reference_num' => 'Reference Num',
            'reference_date' => 'Reference Date',
            'subject' => 'Subject',
            'file_category_id' => 'File Category ID',
            'file_project_id' => 'File Project ID',
            'action_type' => 'Action Type',
            'access_level' => 'Access Level',
            'priority' => 'Priority',
            'is_confidential' => 'Is Confidential',
            'meta_keywords' => 'Meta Keywords',
            'remarks' => 'Remarks',
            'summary' => 'Summary',
            'note_subject' => 'Note Subject',
            'note_comment' => 'Note Comment',
            'file_remarks' => 'File Remarks',
        ];
    }
}