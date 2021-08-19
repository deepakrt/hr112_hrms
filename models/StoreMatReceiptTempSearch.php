<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StoreMatReceiptTemp;

/**
 * StoreMatReceiptTempSearch represents the model behind the search form about `app\models\StoreMatReceiptTemp`.
 */
class StoreMatReceiptTempSearch extends StoreMatReceiptTemp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'MRN_No', 'PO_no', 'Indent_no', 'Dept_code', 'Cost_Centre_Code', 'Emp_code', 'Supplier_Code', 'CLASSIFICATION_CODE', 'ITEM_CAT_CODE', 'ITEM_CODE', 'QtyO', 'QtyS', 'QtyR', 'Flag'], 'integer'],
            [['Receipt_date', 'PO_Date', 'Memo_no', 'Memo_Date', 'Receipt_mode', 'Consignment_no', 'Vehicle_no', 'Measuring_Unit', 'Description', 'Remark'], 'safe'],
            [['Rate_per_unit', 'Sale_tax', 'Sale_tax_per', 'Surcharge', 'ED', 'SED', 'Edu_Cess', 'Cartage', 'Insurance', 'Packing_Forword', 'Discount', 'Octroi'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = StoreMatReceiptTemp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'MRN_No' => $this->MRN_No,
            'Receipt_date' => $this->Receipt_date,
            'PO_no' => $this->PO_no,
            'PO_Date' => $this->PO_Date,
            'Indent_no' => $this->Indent_no,
            'Dept_code' => $this->Dept_code,
            'Cost_Centre_Code' => $this->Cost_Centre_Code,
            'Emp_code' => $this->Emp_code,
            'Supplier_Code' => $this->Supplier_Code,
            'Memo_Date' => $this->Memo_Date,
            'CLASSIFICATION_CODE' => $this->CLASSIFICATION_CODE,
            'ITEM_CAT_CODE' => $this->ITEM_CAT_CODE,
            'ITEM_CODE' => $this->ITEM_CODE,
            'QtyO' => $this->QtyO,
            'QtyS' => $this->QtyS,
            'QtyR' => $this->QtyR,
            'Rate_per_unit' => $this->Rate_per_unit,
            'Sale_tax' => $this->Sale_tax,
            'Sale_tax_per' => $this->Sale_tax_per,
            'Surcharge' => $this->Surcharge,
            'ED' => $this->ED,
            'SED' => $this->SED,
            'Edu_Cess' => $this->Edu_Cess,
            'Cartage' => $this->Cartage,
            'Insurance' => $this->Insurance,
            'Packing_Forword' => $this->Packing_Forword,
            'Discount' => $this->Discount,
            'Octroi' => $this->Octroi,
            'Flag' => $this->Flag,
        ]);

        $query->andFilterWhere(['like', 'Memo_no', $this->Memo_no])
            ->andFilterWhere(['like', 'Receipt_mode', $this->Receipt_mode])
            ->andFilterWhere(['like', 'Consignment_no', $this->Consignment_no])
            ->andFilterWhere(['like', 'Vehicle_no', $this->Vehicle_no])
            ->andFilterWhere(['like', 'Measuring_Unit', $this->Measuring_Unit])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'Remark', $this->Remark]);

        return $dataProvider;
    }
}
