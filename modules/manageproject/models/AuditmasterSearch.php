<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Auditmaster;

/**
 * AuditmasterSearch represents the model behind the search form about `app\models\Auditmaster`.
 */
class AuditmasterSearch extends Auditmaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'orderid', 'activeuser', 'deleted'], 'integer'],
            [['audittype', 'startdate', 'auditagency', 'auditreport', 'reportdate', 'status', 'remarks', 'sessionid', 'updatedon'], 'safe'],
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
    public function search($params, $id)
    {
        if (isset($_SESSION['prjsession'])) {
            $query = Auditmaster::find()->where(['deleted' => 0, 'orderid'=>$_SESSION['prjsession']]);            
        } else {
            $query = Auditmaster::find()->where(['deleted' => 0, 'orderid'=>$id]);
        }
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
            'startdate' => $this->startdate,
            'reportdate' => $this->reportdate,
            'activeuser' => $this->activeuser,
            'deleted' => $this->deleted,
            'updatedon' => $this->updatedon,
        ]);

        $query->andFilterWhere(['like', 'audittype', $this->audittype])
            ->andFilterWhere(['like', 'auditagency', $this->auditagency])
            ->andFilterWhere(['like', 'auditreport', $this->auditreport])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'sessionid', $this->sessionid]);

        return $dataProvider;
    }
}
