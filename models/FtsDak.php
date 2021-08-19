<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "fts_dak".
 */
 

/* @property FtsCategory $category0 */
 
class FtsDak extends \yii\db\ActiveRecord
{

 //public $fts_id=NULL;
 public $send_to_type;
 public $send_to;
 public $send_from;
 public $refrence_no;
 public $file_date;
 public $file_name;
 public $subject;
 public $category;
 public $access_level;
 public $priority;
 public $is_confidential;
 public $meta_keywords;
 public $remarks;
 public $summary;
 public $doc_type;
 public $document;
 public $status;
 public $created_date;
 public $modified_date;
 public $is_active;
 
    /**
     * @inheritdoc
     
    public static function tableName()
    {
        	
    }

    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['send_to_type',  'refrence_no', 'file_date', 'file_name', 'subject', 'category', 'access_level', 'priority', 'is_confidential', 'meta_keywords', 'summary', 'doc_type', 'is_active','document'], 'required'],
            [['send_from', 'category'], 'integer'],
            [['file_date', 'created_date', 'modified_date'], 'safe'],
            [['access_level', 'priority', 'is_confidential', 'status', 'is_active'], 'string'],
            [['refrence_no', 'file_name'], 'string', 'max' => 100],
            [['subject', 'meta_keywords', 'remarks', 'summary'], 'string', 'max' => 200],
            [['doc_type'], 'string', 'max' => 50],
            [['send_to_type'], 'string', 'max' => 1],
            /*
            [['refrence_no','file_name','subject','summary','meta_keywords','remarks'], 'filter', 'filter'=>'trim'],
             
            ['refrence_no', 'match' ,'pattern'=>'/^[A-Za-z0-9]+$/u',
                'message'=> 'Refrence No can contain only alphanumeric characters'],*      
			['file_name', 'match' ,'pattern'=>'/^[A-Za-z]+$/u',
                'message'=> 'File Name can contain only alpha characters'],
            ['subject', 'match' ,'pattern'=>'/^[A-Za-z0-9\s]+$/u',
                'message'=> 'Subject can contain only alphanumeric characters'],  
            ['summary', 'match' ,'pattern'=>'/^[A-Za-z0-9\s]+$/u',
                'message'=> 'Summary can contain only alphanumeric characters'],
           ['meta_keywords', 'match' ,'pattern'=>'/^[A-Za-z0-9\s]+$/u',
                'message'=> 'Meta keywords can contain only alphanumeric characters'],
           ['remarks', 'match' ,'pattern'=>'/^[A-Za-z0-9\s]+$/u',
                'message'=> 'Remarks can contain only alphanumeric characters'], 
			/* [
				['file_date'],'date',
				'format' => 'MM-dd',
				'message'=> 'Remarks can contain only alphanumeric characters'
			], */  
				 
		
				];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fts_id' => 'Fts ID',
            'send_to_type' => 'Send To Type',
            'send_to' => 'Send To',
            'send_from' => 'Send From',
            'refrence_no' => 'Refrence No',
            'file_date' => 'File Date',
            'file_name' => 'File Name',
            'subject' => 'Subject',
            'category' => 'Category',
            'access_level' => 'Access Level',
            'priority' => 'Priority',
            'is_confidential' => 'Is Confidential',
            'meta_keywords' => 'Meta Keywords',
            'remarks' => 'Remarks',
            'summary' => 'Summary',
            'doc_type' => 'Doc Type',
            'status' => 'Status',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory0()
    {
        return $this->hasOne(FtsCategory::className(), ['fts_category_id' => 'category']);
    }
}
