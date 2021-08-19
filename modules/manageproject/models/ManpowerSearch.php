<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\manageproject\models\Manpower;
use yii\helpers\ArrayHelper;

/**
 * ManpowerSearch represents the model behind the search form about `app\models\Manpower`.
 */
class ManpowerSearch extends Manpower
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activeuser', 'deleted', 'id', 'designationid', 'salary', 'totalexperience', 'cdacexperience', 'empcode', 'coi', 'emptype', 'gradepay'], 'integer'],
            [['sessionid', 'updatedon', 'name', 'doj', 'dor', 'dob', 'email', 'phone', 'technologyid', 'qualification', 'doresign', 'gender', 'stafftype', 'grade', 'payband', 'scale', 'dope', 'superannuationdate', 'category'], 'safe'],
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
        $roles_name="";
	$roles=Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
	foreach ($roles as $role)
	{
            $roles_name= $role->name;
	}
	$userid  =   Yii::$app->user->identity->manpowerid;
                
        //Yii::$app->getSession()->addFlash('success', Yii::$app->user);
	
        if($roles_name=='member')
	{            
            $query = Manpower::find()->where(['deleted' => 0, 'id' => $userid])->orderBy('name ASC');
        }
        elseif (Yii::$app->user->can('admin'))  {  
            if (isset($_SESSION['prjsession'])) {
                 $query1 = Manpowermapping::find()->select('manpowerid')->where(['orderid' => $_SESSION['prjsession']])->all();
                 $query = Manpower::find()
                        ->where(['deleted' => 0])                        
                        ->where(['IN', 'id', ArrayHelper::getColumn($query1, 'manpowerid')])
                        ->andWhere(['doresign' => null])
                        ->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');                
            } else {
                
                $query = Manpower::find()
                        ->where(['deleted' => 0])
                        ->andWhere(['doresign' => null])
                        ->orderBy('name ASC');            
            }
            //$query = Manpower::find()->where(['deleted' => 0, 'coi' => $userid])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');            
        } elseif ((Yii::$app->user->can('editor'))) {  
            //$dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            
            if (isset($_SESSION['prjsession'])) {
                 $query1 = Manpowermapping::find()->select('manpowerid')->where(['orderid' => $_SESSION['prjsession']])->all();
                 $query = Manpower::find()
                        ->where(['deleted' => 0, 'cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->where(['IN', 'id', ArrayHelper::getColumn($query1, 'manpowerid')])
                        ->andWhere(['doresign' => null])
                        ->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');                
            } else {
                
                $query = Manpower::find()
                        ->where(['deleted' => 0, 'cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        //->andwhere(['emptype' => 'Contractual'])                        
                        ->andWhere(['doresign' => null])
                        ->orderBy('name ASC');            
            }
            //$query = Manpower::find()->where(['deleted' => 0, 'coi' => $userid])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');            
        }
        else if (Yii::$app->user->can('premium'))
	{           
            $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            
            $query = Manpower::find()
                    ->where(['deleted' => 0])
                    ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                    //->andwhere(['emptype' => 'Contractual'])
                    ->andWhere(['doresign' => null])
                    ->orderBy('name ASC');
            //$query = Manpower::find()->where(['deleted' => 0])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');
        } else if ((Yii::$app->user->can('director')) || (Yii::$app->user->can('hr'))){
            $query = Manpower::find()
                    ->where(['manpower.deleted' => 0])                    
                    ->andWhere(['doresign' => null])
                    ->joinWith('designation')
                    ->joinWith('cdacdept')
                    ->orderBy('cdacdept.shortname, designations.hyrarchy ASC');
            
            
        } else{            
            $query='';
        }
        
        
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,    
            'pagination' =>FALSE
            //'pagination' => [
            //    'pageSize' => 10,
            //    ],
        ]);       
        
        //$tech = NULL;
        
        /*if($params!=NULL && $params['ManpowerSearch']['technologyid']!=NULL){
            $tech = Projecttechnology::find()->where(['technology' => $params['ManpowerSearch']['technologyid']])->all()[0]->id;
                        
            //$dataProvider->query->where(['LIKE', 'technologyid', '%'. $tech[0]->id.'%', false]);
        }*/
            $this->load($params);
        //}
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'activeuser' => $this->activeuser,
            'deleted' => $this->deleted,
            'id' => $this->id,
            'empcode' => $this->empcode,
            'updatedon' => $this->updatedon,
            'doj' => $this->doj,
            'dor' => $this->dor,
            'dob' => $this->dob,
            'designationid' => $this->designationid,
            'phone' => $this->phone,
            'salary' => $this->salary,
            'totalexperience' => $this->totalexperience,
            'cdacexperience' => $this->cdacexperience,
            'doresign' => $this->doresign,
            'coi' => $this->coi,
            'emptype' => $this->emptype,            
            'gradepay' => $this->gradepay,
            'gender' => $this->gender, 
            'stafftype' => $this->stafftype, 
            'grade' => $this->grade, 
            'payband' => $this->payband, 
            'scale' => $this->scale, 
            'dope' => $this->dope, 
            'superannuationdate' => $this->superannuationdate, 
            'category' => $this->category,
            //'technologyid' => ['like', 'technologyid', '%'. $this->technologyid .'%', false]
        ]);

        $query->andFilterWhere(['like', 'sessionid', $this->sessionid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            //->andFilterWhere(['like', 'technologyid', $this->technologyid])
            //->andFilterWhere(['like', 'technologyid', '%'. Projecttechnology::find()->where(['technology' => $this->technologyid])->all()[0]->id .'%', false])
            ->andFilterWhere(['like', 'qualification', $this->qualification]);

        if($params!=NULL && $params['ManpowerSearch']['technologyid']!=NULL){
            
            if(Yii::$app->projectcls->TechnologyFilter($params['ManpowerSearch']['technologyid']) != null)
                $query->andFilterWhere(['IN', 'manpower.id', ArrayHelper::getColumn(Yii::$app->projectcls->TechnologyFilter($params['ManpowerSearch']['technologyid']), 'id')]);
        }
        
        if($params!=NULL && $params['ManpowerSearch']['qualification']!=NULL){
            $query->andFilterWhere(['IN', 'manpower.id', ArrayHelper::getColumn(Yii::$app->projectcls->QualificationFilter($params['ManpowerSearch']['qualification']), 'id')]);
        }
        
        //echo $query->createCommand()->getRawSql();


        
        return $dataProvider;
    }
    
    public function searchteamstatus($params)
    {        
        $roles_name="";
	$roles=Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
	foreach ($roles as $role)
	{
            $roles_name= $role->name;
	}
	$userid  =   Yii::$app->user->identity->manpowerid;
                
        //Yii::$app->getSession()->addFlash('success', Yii::$app->user);
	
        if($roles_name=='member')
	{            
            $query = Manpower::find()->where(['deleted' => 0, 'id' => $userid])->orderBy('name ASC')->with('manpmapp');
        }elseif (Yii::$app->user->can('admin')) { 
            $query = Manpower::find()->where(['deleted' => 0])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC')->with('manpmapp');            
        } elseif (Yii::$app->user->can('editor')) {  
            $query = Manpower::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC')->with('manpmapp');
            //$query = Manpower::find()->where(['deleted' => 0, 'coi' => $userid])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');            
        }else if (Yii::$app->user->can('premium')){           
            $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            
            $d;            
                        
            foreach ($dept as $dpt){                
                $query2 = Manpower::find()->select('id')->where(['deleted' => 0, 'cdacdeptid' => $dpt])->all();
                
                foreach ($query2 as $m)
                $d[] = $m->id;                
            }
            
            $query = Manpower::find()->where(['deleted' => 0])->andwhere(['emptype' => 'Contractual'])->where(['IN', 'id', $d])->orderBy('name ASC')->with('manpmapp');
            //$query = Manpower::find()->where(['deleted' => 0])->andwhere(['emptype' => 'Contractual'])->orderBy('name ASC');
        } 
        else{            
            $query='';
        }       

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,    
            'pagination' =>FALSE
            //'pagination' => [
            //    'pageSize' => 10,
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
            'activeuser' => $this->activeuser,
            'deleted' => $this->deleted,
            'id' => $this->id,
            'empcode' => $this->empcode,
            'updatedon' => $this->updatedon,
            'doj' => $this->doj,
            'dor' => $this->dor,
            'dob' => $this->dob,
            'designationid' => $this->designationid,
            'phone' => $this->phone,
            'salary' => $this->salary,
            'totalexperience' => $this->totalexperience,
            'cdacexperience' => $this->cdacexperience,
            'doresign' => $this->doresign,
            'coi' => $this->coi,
            'emptype' => $this->emptype,
            'gradepay' => $this->gradepay,
            'gender' => $this->gender, 
            'stafftype' => $this->stafftype, 
            'grade' => $this->grade, 
            'payband' => $this->payband, 
            'scale' => $this->scale, 
            'dope' => $this->dope, 
            'superannuationdate' => $this->superannuationdate, 
            'category' => $this->category
        ]);

        $query->andFilterWhere(['like', 'sessionid', $this->sessionid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'technologyid', $this->technologyid])
            ->andFilterWhere(['like', 'qualification', $this->qualification]);

        return $dataProvider;
    }
}
