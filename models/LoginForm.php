<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $role;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {

		//if ( is_numeric($this->email) ) {}
		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !is_numeric($this->email)) {
           // $this->addError('email', 'Incorrect Email.');
        }
        if (!$this->hasErrors()) {
           $user = $this->getUser();


            if (!$user) {

                /*echo "<pre>==8888="; print_r($user);
                echo "<pre>==656="; print_r($_POST);
        die();*/
                $this->addError('password', 'Incorrect Email or Password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 3600);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {

        if ($this->_user === false) {
           // echo '=========='.$this->role; die;
            $session = Yii::$app->session;
            $session->open();
            $session['user_email'] = Yii::$app->utility->encryptString($this->email);
            $session['user_password'] = Yii::$app->utility->encryptString($this->password);
            //$session['user_role'] = 3;//$this->role;

            // echo "<pre>"; print_r($session); die();
            
            $this->_user = User::findByemail();
        }

               /* echo "<pre>==65006="; print_r($_POST);
        echo "<pre>==99900="; print_r($this->_user);
        die();*/

         return $this->_user;
    }
}
