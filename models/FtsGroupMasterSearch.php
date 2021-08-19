<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FtsGroupMaster;

/**
 * FtsGroupMasterSearch represents the model behind the search form about `app\models\FtsGroupMaster`.
 */
class FtsGroupMasterSearch extends FtsGroupMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'created_by'], 'integer'],
            [['group_name', 'group_description', 'creation_date', 'last_modified_date'], 'safe'],
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
        $query = FtsGroupMaster::find();

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
            'group_id' => $this->group_id,
            'created_by' => $this->created_by,
            'creation_date' => $this->creation_date,
            'last_modified_date' => $this->last_modified_date,
        ]);

        $query->andFilterWhere(['like', 'group_name', $this->group_name])
            ->andFilterWhere(['like', 'group_description', $this->group_description]);

        return $dataProvider;
    }
}
