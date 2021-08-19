<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Appraisal".
 *
 * @property int $id
 * @property string $title
 * @property string $feedback
 * @property int $deleted
 * @property string $sdate
 * @property string $uplodatedby
 * @property string $lastupdate
 * @property int $status
 */
class Appraisal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Appraisal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'feedback', 'deleted', 'sdate', 'uploadedby', 'lastupdate', 'status'], 'required'],
           // [['title', 'feedback'], 'string'],
            [['title', 'job_description'], 'string'],
            [['title', 'achievement'], 'string'],
             [['title', 'document'], 'string'],
            [['title', 'authority1_comment'], 'string'],
              [['title', 'authority2_comment'], 'string'],
              [['title', 'request_comment'], 'string'],
            [['deleted', 'status'], 'integer'],
             [['titile', 'request_status'], 'integer'],
                [['titile', 'request_count'], 'integer'],
            [['sdate', 'lastupdate'], 'safe'],
            [['uploadedby'], 'string', 'max' => 240],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Name',
            'Feedback' => 'feedback',
            'job_description' => 'Job Description',
            'achievement' => 'Achievement',
            'deleted' => 'Deleted',
            'sdate' => 'Sdate',
            'uploadedby' => 'uploadedby',
            'lastupdate' => 'Lastupdate',
            'status' => 'Status',
            'authority1_comment' => 'Comment',
            'authority2_comment' => 'Feedback',
            'request_status' => 'Status',
            'request_comment' => 'Comment',
             'request_count' => 'request_count',
            
        ];
    }
}
