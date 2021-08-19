<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "store_material_purchase_request_item".
 *
 * @property int $item_id
 * @property int $emp_id
 * @property int $req_id
 * @property int $id_item
 * @property int $item_name
 * @property string $item_specification
 * @property string $item_doc
 * @property int $quantity_required
 * @property int $approx_cost
 * @property string $purpose
 * @property string $created_date
 */
class StoreMaterialPurchaseRequestItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_material_purchase_request_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['req_id','emp_id', 'item_name', 'id_item', 'item_specification', 'quantity_required', 'purpose', 'created_date'], 'required'],
            [['req_id','id_item', 'quantity_required'], 'integer'],
            [['approx_cost'], 'number'],
            [['item_specification','item_doc', 'purpose'], 'string'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'emp_id' => 'Emp ID',
            'req_id' => 'Req ID',
            'id_item' => 'ID Item',
            'item_name' => 'Item Name',
            'item_specification' => 'Item Specification',
            'item_doc' => 'Specification doc',
            'quantity_required' => 'Quantity Required',
            'approx_cost' => 'Approx Cost',
            'purpose' => 'Purpose',
            'created_date' => 'Created Date',
        ];
    }
}
