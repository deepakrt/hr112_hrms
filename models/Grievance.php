<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grievance".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $complaint_type
 * @property int $status
 * @property string $sdate
 * @property string $lastupdate
 * @property int $createdby
 * @property int $sent_to
 * @property string $dockerno
 * @property string $filename
 */
class Grievance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grievance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'complaint_type', 'status', 'sdate', 'lastupdate', 'createdby',  'docketno'], 'required'],
            [['title', 'description','authority1_comment','authority2_comment'], 'string'],
            [['status', 'createdby'], 'integer'],
            [['sdate', 'lastupdate'], 'safe'],
            [['complaint_type', 'docketno', 'filename'], 'string', 'max' => 240],
            [['filename'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
       
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'complaint_type' => 'Complaint Type',
            'status' => 'Status',
            'sdate' => 'Sdate',
            'lastupdate' => 'Lastupdate',
            'createdby' => 'Createdby',            
            'docketno' => 'Docketno',
            'filename' => 'Filename',
            'authority1_comment' => 'FLA Comment',
            'authority2_comment' => 'SLA Comment',
        ];
    }
}
