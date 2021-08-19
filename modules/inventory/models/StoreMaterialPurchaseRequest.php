<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "store_material_purchase_request".
 *
 * @property int $id
 * @property int $voucher_no
 * @property string $req_type
 * @property string $request_date
 * @property int $division
 * @property int $emp_code
 * @property string $item_name
 * @property string $item_specification
 * @property string $item_doc
 * @property int $quantity_required
 * @property int $approx_cost
 * @property string $item_purpose
 * @property string $project_id
 * @property string $project
 * @property int $project_funds
 * @property string $project_head
 * @property string $remarks
 * @property int $role
 * @property int $flag
 * @property int $FLA
 * @property int $HOD_ID
 * @property string $Approved_FLA
 * @property string $Approved_HOD
 * @property string $available_in_store
 * @property string $approved_by_FM
 * @property string $approved_by_CH
 * @property string $approval_date
 * @property string $purchase_received_date
 * @property string $purchase_status
 */
class StoreMaterialPurchaseRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	public $emp_name; 
	public $item_name; 
	public $units; 
	public $item_specification; 
	public $item_doc; 
	public $quantity_required; 
	public $approx_cost; 
	public $item_purpose; 
	public $underproject; 
	 
    public static function tableName()
    {
        return 'store_material_purchase_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'voucher_no', 'division', 'quantity_required', 'project_funds', 'role', 'flag', 'project_id'], 'integer'],
			 [['approx_cost'], 'number'],
            [['voucher_no', 'request_date', 'division', 'emp_code', 'remarks', 'role', 'flag', 'FLA'], 'required'],
            [['req_type', 'item_specification', 'item_purpose', 'remarks', 'available_in_store', 'approved_by_FM', 'approved_by_CH'], 'string'],
			[['item_doc'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf','mimeTypes' => 'application/pdf'],
            [['request_date', 'approval_date', 'purchase_received_date'], 'safe'],
            [['item_name','item_specification', 'project', 'project_head', 'purchase_status'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voucher_no' => 'Voucher No',
            'req_type' => 'Req Type',
            'request_date' => 'Request Date',
            'division' => 'Division',
            'emp_code' => 'Emp Code',
            'item_name' => 'Item Name',
            'item_specification' => 'Item Specification',
            'item_doc' => 'Specification Doc (If Any)',
            'quantity_required' => 'Quantity Required',
            'approx_cost' => 'Cost per Item(Approx)',
            'item_purpose' => 'Item Purpose',
            'project_id' => 'Project ID',
            'project' => 'Project Name',
            'project_funds' => 'Project Funds',
            'project_head' => 'Project Head',
            'underproject' => 'Procurement Under Project',
            'remarks' => 'Description',
            'role' => 'Role',
            'flag' => 'Flag',
            'FLA' => 'Fla',
            'HOD_ID' => 'Hod ID',
           // 'Approved_by_FLA' => 'Approved Fla',
            //'Approved_by_HOD' => 'Approved Hod',
            'available_in_store' => 'Available In Store',
            'approved_by_FM' => 'Approved By Fm',
            'approved_by_CH' => 'Approved By Ch',
            'approval_date' => 'Approval Date',
            'purchase_received_date' => 'Purchase Received Date',
            'purchase_status' => 'Purchase Status',
        ];
    }
}
