<?php

namespace app\modules\manageproject\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Project_type;
use yii\helpers\ArrayHelper;

/**
 * ProjectsSearch represents the model behind the search form about `app\models\Projects`.
 */
class ProjectdetailSearch extends Projectdetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activeuser', 'orderid', 'projecttypeid',  'milestoneid', 'technologyid', 'finaloutcome', 'completionreport', 'appreciationcert', 'referenceid', 'deleted'], 'integer'],
            [['projectrefno', 'projectstartdate', 'expectedenddate', 'objectives', 'actualcompletiondate', 'remarks', 'sessionid', 'updatedon', 'filenumber', 'status'], 'safe'],
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
        if(Yii::$app->user->identity->role_name == 'FLA'){
            $query = Ordermaster::find()
                        ->where(['deleted' => 0, 'cdacdeptid'=>Yii::$app->user->identity->dept_id])
                        ->with('projects');            
        }
            /*    $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            
        
            

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }else if (Yii::$app->user->can('admin')) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']]);                        
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0]);                        
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            }                    

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }else if ((Yii::$app->user->can('editor'))) {              
            
            //$dept = Cdacdeptmap::find()->select('deptid')->where(['manpowerid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession'], 'cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            }
        
            

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }else if ( Yii::$app->user->can('premium')) {              
            
            $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            }
        
            

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }
        
        else{
            
            $query1 = Ordermaster::find()->select('id')->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                            ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])->andwhere(['deleted'=>0])
                    ->union($query1)->all();

            $query = Ordermaster::find()->where(['IN', 'id', ArrayHelper::getColumn($query2, 'orderid')]);
        }
        */
        
        // add conditions that should always apply here
        if($query==''){
            $dataProvider=null;
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                /*'pagination' => [
                    'pageSize' => 7,
                    ], 
                 * 
                 */
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    
    
    
    public function searchOngoing($params)
    {        
        if(Yii::$app->user->identity->role_name == 'FLA'){
            $query = Ordermaster::find()
                        ->where(['pmis_ordermaster.deleted' => 0, 'pmis_ordermaster.cdacdeptid'=>Yii::$app->user->identity->dept_id])
                        ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                        ->joinWith(['projects']);
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);
                
                
            /*$query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            
            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');*/
        }
        
        
        else if (Yii::$app->user->can('admin')) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']]);                        
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0]);                        
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            }                    

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }else if ((Yii::$app->user->can('editor'))) {              
            
            //SELECT `ordermaster`.`id` FROM `ordermaster` LEFT JOIN `projects` ON `ordermaster`.`id` = `projects`.`Orderid` WHERE ((`ordermaster`.`deleted`=0) AND (`ordermaster`.`cdacdeptid`=1)) AND (`projects`.`status`='On Going') AND (`projects`.`deleted`=0)
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('pmis_ordermaster.id')
                        ->where(['pmis_ordermaster.deleted' => 0, 'pmis_ordermaster.id' => $_SESSION['prjsession'], 'pmis_ordermaster.cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])
                        ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                        ->joinWith(['projectdetail']);
                        
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('pmis_ordermaster.id')
                        ->where(['pmis_ordermaster.deleted' => 0, 'pmis_ordermaster.cdacdeptid'=>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])
                        ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                        ->joinWith(['projects']);
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')]);
                
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->union($query1)->all();
            }
        
            

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }else if ( Yii::$app->user->can('premium')) {              
            
            $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('pmis_ordermaster.id')
                        ->where(['pmis_ordermaster.deleted' => 0, 'pmis_ordermaster.id' => $_SESSION['prjsession']])
                        ->andwhere(['IN', 'pmis_ordermaster.cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                        ->joinWith(['projectdetail']);                
                $query2 = Investigator::find()->select('pmis_investigator.orderid')->where(['pmis_investigator.chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['pmis_investigator.coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['pmis_investigator.deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                    ->joinWith(['projectdetail'])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('pmis_ordermaster.id')
                        ->where(['pmis_ordermaster.deleted' => 0])
                        ->andwhere(['IN', 'pmis_ordermaster.cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                        ->joinWith(['projectdetail']);
                
                
                
                $query2 = Investigator::find()->select('pmis_investigator.orderid')->where(['pmis_investigator.chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['pmis_investigator.coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['pmis_investigator.deleted'=>0])
                    ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                    ->joinWith(['projectdetail'])
                    ->union($query1)->all();
                
            }
        
            
            $query = Investigator::find()->where(['IN', 'pmis_investigator.orderid', ArrayHelper::getColumn($query2, 'orderid')])
                    ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                    ->joinWith(['projectdetail'])
                    ->with('projects');
        }
        
        else{
            
            $query1 = Ordermaster::find()->select('id')->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                            ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])->andwhere(['deleted'=>0])
                    ->union($query1)->all();

            $query = Ordermaster::find()
                    ->where(['IN', 'pmis_ordermaster.id', ArrayHelper::getColumn($query2, 'orderid')])
                    ->andWhere(['pmis_projectdetail.status' => 'On Going'])
                    ->joinWith(['projectdetail']);
        }
        
        
        // add conditions that should always apply here
        if($query==''){
            $dataProvider=null;
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                /*'pagination' => [
                    'pageSize' => 7,
                    ], 
                 * 
                 */
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    
    /*public function searchOngoing($params)
    {        
        $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
        $proj = Projects::find()->select('orderid')->where(['status' => "On Going", 'deleted'=>0])->all();
            
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        } elseif (Yii::$app->user->can('premium')) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        } else{
            
            $query1 = Ordermaster::find()->select('id')
                    ->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()])
                    ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                    ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->andwhere(['deleted'=>0])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')])
                        ->union($query1)->all();

            $query = Ordermaster::find()->where(['IN', 'id', ArrayHelper::getColumn($query2, 'orderid')]);
        }

        // add conditions that should always apply here
        if($query==''){
            $dataProvider=null;
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                'pagination' => [
                    'pageSize' => 7,
                    ], 
                 
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    */
    public function searchCompleted($params)
    {        
        $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
        $proj = Projectdetail::find()->select('orderid')->where(['status' => "Completed", 'deleted'=>0])->all();
            
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        }elseif (Yii::$app->user->can('premium')) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        } else{
            
            $query1 = Ordermaster::find()->select('id')
                    ->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()])
                    ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                    ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->andwhere(['deleted'=>0])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')])
                        ->union($query1)->all();

            $query = Ordermaster::find()->where(['IN', 'id', ArrayHelper::getColumn($query2, 'orderid')]);
        }

        // add conditions that should always apply here
        if($query==''){
            $dataProvider=null;
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                /*'pagination' => [
                    'pageSize' => 7,
                    ], 
                 * 
                 */
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    
    public function searchClosed($params)
    {        
        $dept = Cdacdeptmap::find()->select('deptid')->where(['hodid'=> Yii::$app->user->identity->manpowerid])->distinct()->all(); 
        $proj = Projectdetail::find()->select('orderid')->where(['status' => "Closed", 'deleted'=>0])->all();
            
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        //->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        } elseif (Yii::$app->user->can('premium')) {
            
            if (isset($_SESSION['prjsession'])) {                    
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0, 'id' => $_SESSION['prjsession']])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0, 'orderid' => $_SESSION['prjsession']])
                    ->union($query1)->all();
            } else {
                $query1 = Ordermaster::find()->select('id')
                        ->where(['deleted' => 0])
                        ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
                
                $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                    ->andwhere(['deleted'=>0])
                    ->andwhere(['IN', 'orderid', ArrayHelper::getColumn($proj, 'orderid')])
                    ->union($query1)->all();
            }

            $query = Investigator::find()->where(['IN', 'orderid', ArrayHelper::getColumn($query2, 'orderid')])->with('projects');
        } else{
            
            $query1 = Ordermaster::find()->select('id')
                    ->where(['deleted' => 0, 'activeuser' =>Yii::$app->user->getId()])
                    ->andwhere(['IN', 'cdacdeptid', ArrayHelper::getColumn($dept, 'deptid')])
                    ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')]);
        
        
            $query2 = Investigator::find()->select('orderid')->where(['chiefinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->orWhere(['coinvestigator'=>Yii::$app->user->identity->manpowerid])
                        ->andwhere(['deleted'=>0])
                        ->andwhere(['IN', 'id', ArrayHelper::getColumn($proj, 'orderid')])
                        ->union($query1)->all();

            $query = Ordermaster::find()->where(['IN', 'id', ArrayHelper::getColumn($query2, 'orderid')]);
        }

        // add conditions that should always apply here
        if($query==''){
            $dataProvider=null;
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                /*'pagination' => [
                    'pageSize' => 7,
                    ], 
                 * 
                 */
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    
    public function searchProject($params, $id)
    {
        if(Yii::$app->projectcls->MemberRole()=='member')
	{          
            $query='';
            //$query = Projects::find()->where(['deleted' => 0, 'id' => $userid])->orderBy('name ASC');
        }	
        elseif ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {             
            if (in_array(Yii::$app->projectcls->ProjectDetails($id)->orderid, Yii::$app->projectcls->MapMember())) { 
                $query = Projectdetail::find()->where(['deleted' => 0, 'id' => $id]);
            } else {
                $query='';
            }
        }
        else if (Yii::$app->user->can('premium'))
	{           
            $query = Projectdetail::find()->where(['deleted' => 0, 'id' => $id]);
        } 
        else{            
            $query='';
        }       

        // add conditions that should always apply here

        if($query==''){
            $dataProvider=null;            
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => FALSE,
                /*'pagination' => [
                    'pageSize' => 7,
                    ], */
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
                'activeuser' => $this->activeuser,
                'orderid' => $this->orderid,
                'projecttypeid' => $this->projecttypeid,
                //'investigatorid' => $this->investigatorid,
                //'coinvestigatorid' => $this->coinvestigatorid,
                'projectstartdate' => $this->projectstartdate,
                'expectedenddate' => $this->expectedenddate,
                'milestoneid' => $this->milestoneid,
                'technologyid' => $this->technologyid,
                //'databaseused' => $this->databaseused,
                //'manpowerid' => $this->manpowerid,
                'finaloutcome' => $this->finaloutcome,
                'completionreport' => $this->completionreport,
                'appreciationcert' => $this->appreciationcert,
                'actualcompletiondate' => $this->actualcompletiondate,
                'referenceid' => $this->referenceid,
                'deleted' => $this->deleted,
                'updatedon' => $this->updatedon,
                'filenumber' => $this->filenumber,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'projectrefno', $this->projectrefno])
                ->andFilterWhere(['like', 'objectives', $this->objectives])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sessionid', $this->sessionid]);
        }
        return $dataProvider;
    }
    
    public function searchReport($params)
    {
        // create ActiveQuery
        
        if ((Yii::$app->user->can('admin')) ||(Yii::$app->user->can('editor'))) {
            $query = Projectdetail::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
        } else if(Yii::$app->user->can('premium')){
            $query = Projectdetail::find()->where(['deleted' => 0]);
        }
        else if(Yii::$app->projectcls->MemberRole()=='member')
	{          
            $query = Projectdetail::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
            //$query = Projects::find()->where(['deleted' => 0, 'id' => $userid])->orderBy('name ASC');
        } else{            
            $query = Projectdetail::find()->where(['deleted' => 0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid]);
        }        
        
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['ordermaster', 'projectType']);

        if($query==''){
            $dataProvider=null;            
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            // Important: here is how we set up the sorting
            // The key is the attribute name on our "TourSearch" instance
            $dataProvider->sort->attributes['projectname'] = [
                // The tables are the ones our relation are configured to
                // in my case they are prefixed with "tbl_"
                'asc' => ['ordermaster.projectname' => SORT_ASC],
                'desc' => ['ordermaster.projectname' => SORT_DESC],
            ];
            // Lets do the same with country now
            $dataProvider->sort->attributes['ProjectType'] = [
                'asc' => ['projectType.type' => SORT_ASC],
                'desc' => ['projectType.type' => SORT_DESC],
            ];
            // No search? Then return data Provider
            if (!($this->load($params) && $this->validate())) {
                return $dataProvider;
            }
            // We have to do some search... Lets do some magic
            $query->andFilterWhere([
                //... other searched attributes here
            ])
            // Here we search the attributes of our relations using our previously configured
            // ones in "TourSearch"
            ->andFilterWhere(['like', 'ordermaster.projectname', $this->ordermaster->projectname])
            ->andFilterWhere(['like', 'projectType.type', $this->project_type->type]);
        }
        return $dataProvider;
    }
}
