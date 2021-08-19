<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_history".
 *
 * @property integer $file_history_id
 * @property integer $file_id
 * @property string $fwd_to
 * @property integer $dak_group_id
 * @property string $fwd_emp_code
 * @property string $fwd_by
 * @property string $created_date
 * @property string $is_active
 */
class EfileDakHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_history_id' => 'File History ID',
            'file_id' => 'File ID',
            'fwd_to' => 'G: Group, E:Emp, A: All',
            'dak_group_id' => 'Dak Group ID',
            'fwd_emp_code' => 'emp code if fwd to employee',
            'fwd_emp_dept_id' => 'emp dept id if fwd to employee',
            'fwd_by' => 'emp code',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
        ];
    }
}
