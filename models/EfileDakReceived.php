<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_received".
 *
 * @property integer $rec_id
 * @property string $dak_number
 * @property string $mode_of_rec
 * @property string $rec_date
 * @property string $rec_from
 * @property string $org_state
 * @property string $org_district
 * @property string $org_address
 * @property string $dak_summary
 * @property string $dak_remarks
 * @property string $dak_document
 * @property integer $dak_fwd_dept
 * @property string $dak_fwd_to
 * @property string $is_active
 * @property string $status
 * @property string $forwaded_by
 * @property string $forwarded_date
 */
class EfileDakReceived extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_received';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['dak_number', 'rec_date', 'rec_from', 'org_address', 'dak_summary', 'dak_fwd_to', 'forwaded_by'], 'required'],
//            [['rec_date', 'forwarded_date'], 'safe'],
//            [['dak_summary', 'dak_remarks', 'dak_document', 'is_active'], 'string'],
//            [['dak_fwd_dept'], 'integer'],
//            [['dak_number', 'mode_of_rec', 'rec_from', 'org_state', 'org_district'], 'string', 'max' => 100],
//            [['org_address'], 'string', 'max' => 500],
//            [['dak_fwd_to', 'status', 'forwaded_by'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rec_id' => 'Rec ID',
            'dak_number' => 'Dak Number',
            'mode_of_rec' => 'Mode Of Rec',
            'rec_date' => 'Rec Date',
            'rec_from' => 'Rec From',
            'org_state' => 'Org State',
            'org_district' => 'Org District',
            'org_address' => 'Org Address',
            'dak_summary' => 'Dak Summary',
            'dak_remarks' => 'Dak Remarks',
            'dak_document' => 'Dak Document',
            'dak_fwd_dept' => 'Dak Fwd Dept',
            'dak_fwd_to' => 'Dak Fwd To',
            'is_active' => 'Is Active',
            'status' => 'Status',
            'forwaded_by' => 'Forwaded By',
            'forwarded_date' => 'Forwarded Date',
        ];
    }
}
