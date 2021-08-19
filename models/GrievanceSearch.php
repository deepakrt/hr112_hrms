<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Grievance;

/**
 * GrievanceSearch represents the model behind the search form of `app\models\Grievance`.
 */
class GrievanceSearch extends Grievance
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'createdby'], 'integer'],
            [['title', 'description', 'complaint_type', 'sdate', 'lastupdate', 'docketno', 'filename'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Grievance::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'sdate' => $this->sdate,
            'lastupdate' => $this->lastupdate,
            'createdby' => $this->createdby,
           
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'complaint_type', $this->complaint_type])
            ->andFilterWhere(['like', 'docketno', $this->docketno])
            ->andFilterWhere(['like', 'filename', $this->filename]);

        return $dataProvider;
    }
}
