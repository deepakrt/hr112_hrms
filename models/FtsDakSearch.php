<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FtsDak;

/**
 * FtsDakSearch represents the model behind the search form about `app\models\FtsDak`.
 */
class FtsDakSearch extends FtsDak
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dak_id', 'send_to_type', 'send_to', 'send_from', 'category'], 'integer'],
            [['refrence_no', 'file_date', 'file_name', 'subject', 'access_level', 'priority', 'is_confidential', 'meta_keywords', 'remarks', 'summary', 'doc_type', 'status', 'created_date', 'modified_date', 'is_active'], 'safe'],
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
        $query = FtsDak::find();

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
            'dak_id' => $this->dak_id,
            'send_to_type' => $this->send_to_type,
            'send_to' => $this->send_to,
            'send_from' => $this->send_from,
            'file_date' => $this->file_date,
            'category' => $this->category,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'refrence_no', $this->refrence_no])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'access_level', $this->access_level])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'is_confidential', $this->is_confidential])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'doc_type', $this->doc_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
