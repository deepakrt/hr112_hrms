<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Manpowermapping;

/**
 * ManpowermappingSearch represents the model behind the search form about `frontend\models\Manpowermapping`.
 */
class ManpowermappingSearch extends Manpowermapping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'orderid', 'manpowerid', 'salary', 'activeuser', 'deleted'], 'integer'],
            [['mandays'], 'number'],
            [['workstartdate', 'sactionpost', 'sessionid', 'updatedon'], 'safe'],
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
        $query = Manpowermapping::find();

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
            'orderid' => $this->orderid,
            'manpowerid' => $this->manpowerid,
            'mandays' => $this->mandays,
            'workstartdate' => $this->workstartdate,
            'salary' => $this->salary,
            'activeuser' => $this->activeuser,
            'deleted' => $this->deleted,
            'updatedon' => $this->updatedon,
        ]);

        $query->andFilterWhere(['like', 'sactionpost', $this->sactionpost])
            ->andFilterWhere(['like', 'sessionid', $this->sessionid]);

        return $dataProvider;
    }
}
