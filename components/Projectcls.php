<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\manageproject\models\Manpowermapping;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\Projectdetail;

class Projectcls extends Component
{
    public function AllProjects(){   
        if( Yii::$app->user->can('director') || (Yii::$app->user->can('admin'))) {
            return Ordermaster::find()->where(['deleted'=>0])->orderBy('projectname ASC')->all();
        }else if(Yii::$app->user->identity->e_id != null){ 
            return Ordermaster::find()->where(['deleted'=>0, 'cdacdeptid' =>Yii::$app->user->identity->dept_id])->orderBy('projectname ASC')->all();
            /*if(Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid == 4){
                return Ordermaster::find()->where(['deleted'=>0, 'cdacdeptid' =>3])->orderBy('clientid, projectname ASC')->all();
            }else {
                return Ordermaster::find()->where(['deleted'=>0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->orderBy('projectname ASC')->all();
            }*/
        } else{
            Yii::$app->getSession()->addFlash('danger', 'User does not exists!');
        }
    }
    
    public function mapEd($id){            
        $map = Manpowermapping::find()
                ->where(['pmis_manpowermapping.manpowerid' => $id])
                ->andWhere(['pr_project_list.actualcompletiondate' => NULL])                
                ->andWhere(['!=', 'projectrefno', 'Departmental Misc Work'])
                ->joinWith('project', false, 'LEFT JOIN')
                ->orderBy('pr_project_list.actualcompletiondate DESC')        
                ->all();        
        
        
                            
        if($map == NULL)
            return null;
        else
            return $map; 
    }
    
    public function SalaryPercentageToday($id){
        $total=0;
        
                    for ($j =0; $j<sizeof(Yii::$app->projectcls->mapEd($id)); $j++){
                       
                        if(date('Y-m-d',strtotime(date('Y-m-d H:i:s'))) > date('Y-m-d',strtotime(Yii::$app->projectcls->mapEd($id)[$j]->project->end_date))) {
                            $total += 0;
                        }else{
                            $total += Yii::$app->projectcls->mapEd($id)[$j]->salary ;
                        }
                    }
                    
        return $total ;
    }
    
    public function SelectOrder($id){
        $order= Ordermaster::find()->where(['id'=>$id])->all();
        
        if($order==NULL)
            return null;
        else
            return $order[0];
    }
    
    public function Projectwithorder($id){
        $prj = Projectdetail::find()->where(['orderid'=>$id])->all(); 
        
        if($prj == NULL)
            return null;
        else
            return $prj;
    }
   
}