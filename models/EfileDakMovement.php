<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_movement".
 *
 * @property integer $id
 * @property integer $file_id
 * @property string $fwd_to
 * @property integer $dak_group_id
 * @property string $fwd_emp_code
 * @property string $is_time_bound
 * @property string $fwd_file_type
 * @property string $response_date
 * @property string $status
 * @property string $is_reply_required
 * @property string $reply_status
 * @property string $fwd_by
 * @property string $fwd_date
 * @property string $is_active
 */
class EfileDakMovement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_movement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'fwd_to', 'fwd_emp_code', 'is_time_bound', 'fwd_file_type', 'fwd_by', 'fwd_date'], 'required'],
            [['file_id', 'dak_group_id'], 'integer'],
            [['fwd_to', 'is_time_bound', 'fwd_file_type', 'is_reply_required', 'reply_status', 'is_active'], 'string'],
            [['response_date', 'fwd_date'], 'safe'],
            [['fwd_emp_code', 'status', 'fwd_by'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'fwd_to' => 'G: Group, E:Emp, A: All Emp',
            'dak_group_id' => 'Dak Group ID',
            'fwd_emp_code' => 'Fwd Emp Code',
            'is_time_bound' => 'Is Time Bound',
            'fwd_file_type' => 'Fwd File Type',
            'response_date' => 'Response Date',
            'status' => 'Status',
            'is_reply_required' => 'Is Reply Required',
            'reply_status' => 'Reply Status',
            'fwd_by' => 'emp code',
            'fwd_date' => 'Fwd Date',
            'is_active' => 'Is Active',
        ];
    }
}
