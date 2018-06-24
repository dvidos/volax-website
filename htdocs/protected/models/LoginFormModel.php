<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginFormModel extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that email and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// email and password are required
			array('email, password', 'required'),
			// email should be a valid email or password
			array('email', 'identifyingEmail'),
			// password needs to be authenticated
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Email ή Ονομα χρήστη',
			'password'=>'Κωδικός πρόσβασης',
			'rememberMe'=>'Αυτόματη σύνδεση',
		);
	}

	/**
	 * our email can be username or email, but it should point to a unique record.
	 */
	public function identifyingEmail()
	{
		$user = $this->findUser();
		if ($user == null)
			$this->addError('email', 'Δεν ταυτοποιήθηκε χρήστης με τέτοιο όνομα ή email');
	}
	
	
	/**
	 * Find a user based on our email, as username or email.
	 * But look for a unique record.
	 */
	public function findUser()
	{
		// first, try to find a unique email address (usually the case)
		$users = User::model()->findAll('LOWER(email)=?', array(strtolower($this->email)));
		if (count($users) == 1)
			return $users[0];

		// for fallback, try a unique user name... (we have three for jimel with the same email)
		$users = User::model()->findAll('LOWER(username)=?', array(strtolower($this->email)));
		if (count($users) == 1)
			return $users[0];
		
		// finally, give up!
		return null;
	}
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->email, $this->password);
		if(!$this->_identity->authenticate())
			$this->addError('password','Incorrect email, username or password.');
	}
	
	/**
	 * Logs in the user using the given email and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600 * 24 * 30 * 3: 0; // 3 months
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
