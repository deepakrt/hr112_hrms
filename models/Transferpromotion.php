<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transferpromotion".
 *
 * @property int $id
 * @property string $title
 * @property string $remarks
 * @property string $sdate
 * @property int $status
 * @property string $createdby
 * @property string $lastupdate
 * @property string $action_emp
 * @property string $is_active
 */
class Transferpromotion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transferpromotion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'remarks', 'sdate', 'status', 'createdby', 'lastupdate', 'action_emp', 'is_active'], 'required'],
            [['title', 'remarks','request_for'], 'string'],
            [['sdate', 'lastupdate'], 'safe'],
            [['status'], 'integer'],
            [['createdby', 'action_emp'], 'string', 'max' => 240],
            [['is_active'], 'string', 'max' => 200],
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
            'remarks' => 'Remarks',
            'sdate' => 'Date',
            'status' => 'Status',
            'createdby' => 'Createdby',
            'lastupdate' => 'Lastupdate',
            'action_emp' => 'Action Emp',
            'is_active' => 'Is Active',
            'request_for' => 'Request For',
        ];
    }
}
