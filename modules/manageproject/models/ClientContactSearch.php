<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\manageproject\models\ClientContact;

/**
 * ClientContactSearch represents the model behind the search form about `app\models\ClientContact`.
 */
class ClientContactSearch extends ClientContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cdacdeptid', 'orderid', 'userid'], 'integer'],
            [['name', 'email', 'remarks', 'updatedon', 'phone', 'mobile'], 'safe'],
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
        $query = ClientContact::find();
        $query->joinWith(['deptName']);
        
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            if (isset($_SESSION['prjsession'])) {                
                $oid = Ordermaster::find()->where(['id' => $_SESSION['prjsession']])->all();                                
                $query->andwhere(['client_contact.deleted' => 0, 'client_contact.clientid' => $oid[0]->clientid, 'client_contact.cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->all();
            } else {
                $query->andwhere(['client_contact.deleted' => 0, 'client_contact.cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->all();
            }
            //$query = Ordermaster::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
        } else if(Yii::$app->user->can('premium')){
            if (isset($_SESSION['prjsession'])) {                
                $oid = Ordermaster::find()->where(['id' => $_SESSION['prjsession']])->all();                                
                $query->andwhere(['client_contact.deleted' => 0, 'client_contact.clientid' => $oid[0]->clientid])->all();
            } else {                
                $query->andwhere(['client_contact.deleted' => 0])->all();
            }
            //$query = Ordermaster::find()->where(['deleted' => 0]);
        }
        else{
            $query->andwhere(['client_contact.deleted' => 0, 'client_contact.cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->all();
        }
        
        // add conditions that should always apply here

        //echo $query->createCommand()->getRawSql();
//die();

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
            /*'pagination' => [
                'pageSize' => 5,
                ], */
        ]);

        $this->load($params);

        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'orderid' => $this->orderid,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'updatedon' => $this->updatedon,
            'userid' => $this->userid,
            'cdacdeptid' => $this->cdacdeptid,
        ]);

        $dataProvider->sort->attributes['country'] = [
            'asc'  => ['country.name' => SORT_ASC],
            'desc' => ['country.name' => SORT_DESC],
        ];                
        
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }    
}
