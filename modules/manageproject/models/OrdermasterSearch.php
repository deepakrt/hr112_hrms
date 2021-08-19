<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\manageproject\models\Ordermaster;
use yii\helpers\ArrayHelper;

/**
 * OrdermasterSearch represents the model behind the search form about `app\models\Ordermaster`.
 */
class OrdermasterSearch extends Ordermaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'clientid', 'cdacdeptid', 'ordertype', 'fundingagency', 'activeuser', 'deleted'], 'integer'],
            [['orderdate', 'number', 'sessionid', 'updatedon'], 'safe'],
            [['amount'], 'number'],
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
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            $query = Ordermaster::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
        } else if(Yii::$app->user->can('premium')){
            $query = Ordermaster::find()->where(['deleted' => 0]);
        }
        else{
            $query1 = Ordermaster::find()->select('id')->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                            ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])->andwhere(['deleted'=>0])
                    ->union($query1)->all();

            $query = Ordermaster::find()->where(['IN', 'id', ArrayHelper::getColumn($query2, 'orderid')]);
        }
        
       
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
            //'pagination' => [
            //    'pageSize' => 5,
            //    ], 
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
            'clientid' => $this->clientid,
            'orderdate' => $this->orderdate,
            'amount' => $this->amount,
            'ordertype' => $this->ordertype,
            'fundingagency' => $this->fundingagency,
            'activeuser' => $this->activeuser,
            'deleted' => $this->deleted,
            'updatedon' => $this->updatedon,
            'projectname'=> $this->projectname,
            'cdacdeptid' => $this->cdacdeptid,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'sessionid', $this->sessionid]);

        return $dataProvider;
    }
    
   
}
