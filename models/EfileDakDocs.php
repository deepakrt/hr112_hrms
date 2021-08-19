<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_docs".
 *
 * @property integer $dakdocs_id
 * @property integer $file_id
 * @property string $attach_with
 * @property integer $noteid
 * @property integer $tag_id
 * @property string $doc_ext_type
 * @property string $docs_path
 * @property string $added_by
 * @property string $created_date
 * @property string $is_active
 */
class EfileDakDocs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_docs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'attach_with', 'doc_ext_type', 'docs_path', 'added_by', 'created_date'], 'required'],
            [['file_id', 'noteid', 'tag_id'], 'integer'],
            [['attach_with', 'doc_ext_type', 'is_active'], 'string'],
            [['created_date'], 'safe'],
            [['docs_path'], 'string', 'max' => 255],
            [['added_by'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dakdocs_id' => 'Dakdocs ID',
            'file_id' => 'File ID',
            'attach_with' => 'Attach With',
            'noteid' => 'Noteid',
            'tag_id' => 'Tag ID',
            'doc_ext_type' => 'Doc Ext Type',
            'docs_path' => 'Docs Path',
            'added_by' => 'Emp Code',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
        ];
    }
}
