<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrProjectTasks;

/**
 * PrProjectTasksSearch represents the model behind the search form of `app\models\PrProjectTasks`.
 */
class PrProjectTasksSearch extends PrProjectTasks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'parent_task_id', 'project_id', 'task_name', 'task_description', 'assigned_to', 'assigned_by', 'type', 'progress'], 'integer'],
            [['priority', 'start_date', 'task_end_date_fla', 'task_end_date_emp', 'remarks', 'state', 'created_on', 'updated_on', 'is_active'], 'safe'],
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
        $query = PrProjectTasks::find();

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
            'task_id' => $this->task_id,
            'parent_task_id' => $this->parent_task_id,
            'project_id' => $this->project_id,
            'task_name' => $this->task_name,
            'task_description' => $this->task_description,
            'assigned_to' => $this->assigned_to,
            'assigned_by' => $this->assigned_by,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'task_end_date_fla' => $this->task_end_date_fla,
            'task_end_date_emp' => $this->task_end_date_emp,
            'progress' => $this->progress,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);

        $query->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
