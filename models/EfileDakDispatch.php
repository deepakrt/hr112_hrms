<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_dispatch".
 *
 * @property integer $disp_id
 * @property string $disp_number
 * @property string $disp_date
 * @property string $file_id
 * @property string $disp_summary
 * @property string $mode_of_rec
 * @property string $entry_language
 * @property string $disp_remarks
 * @property string $disp_document
 * @property integer $disp_from_dept
 * @property string $disp_from_emp
 * @property string $dispatch_by
 * @property string $is_active
 * @property string $status
 * @property string $dispatch_date
 */
class EfileDakDispatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['disp_number', 'disp_date', 'mode_of_rec', 'entry_language', 'disp_from_dept', 'disp_from_emp', 'dispatch_by', 'letter_language', 'letter_language', 'status'], 'required'],
            [['disp_date', 'dispatch_date'], 'safe'],
            [['disp_summary', 'entry_language', 'disp_document', 'is_active'], 'string'],
            [['disp_from_dept'], 'integer'],
            [['disp_number', 'file_id', 'mode_of_rec'], 'string', 'max' => 100],
            [['disp_remarks'], 'string', 'max' => 250],
            [['disp_from_emp', 'dispatch_by'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disp_id' => 'Disp ID',
            'disp_number' => 'Disp Number',
            'disp_date' => 'Disp Date',
            'file_id' => 'File ID',
            'disp_summary' => 'Disp Summary',
            'mode_of_rec' => 'Mode Of Rec',
            'entry_language' => 'Entry Language',
            'disp_remarks' => 'Disp Remarks',
            'disp_document' => 'Disp Document',
            'disp_from_dept' => 'Disp From Dept',
            'disp_from_emp' => 'Disp From Emp',
            'dispatch_by' => 'Dispatch By',
            'postal_amount' => 'Postal_amount',
            'postal_date' => 'postal_date',
            'is_active' => 'Is Active',
            'status' => 'Status',
            'dispatch_date' => 'Dispatch Date',
        ];
    }
}
