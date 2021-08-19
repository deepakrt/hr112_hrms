<?php

namespace app\models;
use yii;
use yii\web\Session;
class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    
//    public $email;
//    public $password;
    public $map_id,$e_id, $role,$role_name, $authKey, $accessToken,$employee_code, $email_id, $fname, $lname, $fullname, $gender, $dob, $phone, $address, $city, $state, $zip, $p_address, $p_city, $p_state, $p_zip,$desg_id,$desg_name,$dept_id,$dept_name,$employment_type,$marital_status,$emergency_phone,$contact,$p_contact, $joining_date, $blood_group, $is_active, $emp_image, $emp_signature, $created_date,$designation_id,$authority1,$authority2,$grade_pay_scale, $employmenttype, $pan_number, $name_hindi, $desg_name_hindi,$religion,$belt_no,$rank1,$caste,$passport_detail,$category_id;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = self::findByemail();
        return isset($user) ? new static($user) : null;
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByemail()
    {
        $session = Yii::$app->session;
        $session->open();
        $email = Yii::$app->utility->decryptString($session['user_email']);
        $password = Yii::$app->utility->decryptString($session['user_password']);
//        echo $session['user_role']."<br>";
        $role =3;//Yii::$app->utility->decryptString($session['user_role']);
		if($email=='admin@email.com'){
			$role =1;
		}elseif($email=='director.mohali@cdac.in'){
			$role =19;
		}elseif($email=='store@cdac.in'){
			$role =9;
		}elseif($email=='officereception@cdac.in'){
			$role =18;
		}
        //echo $role; die;
        if(empty($email) OR empty($password) OR empty($role)){
            //            die('ok');
            return false;
        }


        // echo "==== email:".$email."==== password:".$password."==== ROle:".$role;  die();

        $user = Yii::$app->utility->login_auth($email, $password, $role);
    
     // echo "<pre>====---";print_r($user); die;

        if(!empty($user))
        {
            $users = Yii::$app->utility->get_employees($user['e_id']);
            //            echo "<pre>";print_r($users); die;
            if(empty($users)){
                
                return false;
            }
            if(empty($users['emp_image'])){
                $users['emp_image'] = DefaultImageEmployee;
            }
            $user1=$users;
            $session = Yii::$app->session;
            $newrole = $session->get('newrole');
            if(!empty($newrole)){
                $user1['role'] = $newrole;
                $roless = Yii::$app->utility->get_roles($newrole);
                $user1['role_name'] = $roless['role'];
            }else{
                $user1['role'] = $user['role'];
                $user1['role_name'] = $user['role_name'];
            }
            
            $user1['accessToken'] = $user['accessToken'];
            $user1['map_id'] = $user['map_id'];
            
            $user1['e_id'] = $users['employee_code'];
            $user1['grade_pay_scale'] = $users['grade_pay_scale'];
//            echo "<pre>";print_r($user1); die;
            return new static($user1);
           }
            else {
                return false;
            } 
        return null;
        
        
        
//        foreach (self::$users as $user) {
//            if (strcasecmp($user['email'], $email) === 0) {
//                return new static($user);
//            }
//        }
//
//        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->e_id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
		return true;
       // return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
		return true;
        //return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
