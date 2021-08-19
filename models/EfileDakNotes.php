<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_notes".
 *
 * @property integer $noteid
 * @property integer $file_id
 * @property string $note_subject
 * @property string $note_comment
 * @property string $added_by
 * @property string $added_date
 * @property string $file_attach
 * @property string $status
 * @property string $content_type
 * @property string $is_active
 */
class EfileDakNotes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_notes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'added_by', 'added_date', 'file_attach', 'status', 'content_type'], 'required'],
            [['file_id'], 'integer'],
            [['note_subject', 'note_comment', 'file_attach', 'status', 'content_type', 'is_active'], 'string'],
            [['added_date'], 'safe'],
            [['added_by'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'noteid' => 'Noteid',
            'file_id' => 'File ID',
            'note_subject' => 'Note Subject',
            'note_comment' => 'Note Comment',
            'added_by' => 'emp code',
            'added_date' => 'Added Date',
            'file_attach' => 'File Attach',
            'status' => 'Status',
            'content_type' => 'N: Note, R:Remarks',
            'is_active' => 'Is Active',
        ];
    }
}
