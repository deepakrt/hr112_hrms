<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FtsCategory;

/**
 * FtsCategorySearch represents the model behind the search form about `app\models\FtsCategory`.
 */
class FtsCategorySearch extends FtsCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fts_category_id', 'cat_name'], 'integer'],
            [['is_hierarchical', 'description', 'is_active'], 'safe'],
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
        $query = FtsCategory::find();

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
            'fts_category_id' => $this->fts_category_id,
            'cat_name' => $this->cat_name,
        ]);

        $query->andFilterWhere(['like', 'is_hierarchical', $this->is_hierarchical])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
