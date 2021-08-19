<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efile_dak_dispatch_address".
 *
 * @property integer $disp_add_id
 * @property string $disp_id
 * @property string $disp_to
 * @property integer $org_state
 * @property integer $org_district
 * @property string $org_address
 */
class EfileDakDispatchAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'efile_dak_dispatch_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disp_id', 'disp_to', 'org_state', 'org_district', 'org_address'], 'required'],
//            [['org_state', 'org_district'], 'integer'],
//            [['disp_id'], 'string', 'max' => 100],
//            [['disp_to'], 'string', 'max' => 500],
//            [['org_address'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disp_add_id' => 'Disp Add ID',
            'disp_id' => 'Disp ID',
            'disp_to' => 'Disp To',
            'org_state' => 'Org State',
            'org_district' => 'Org District',
            'org_address' => 'Org Address',
        ];
    }
}
