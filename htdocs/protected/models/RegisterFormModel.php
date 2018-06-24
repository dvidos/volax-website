<?php

class RegisterFormModel extends CFormModel
{
	public $email;
	public $password;
	public $accept_terms;
	public $verifyCode;

	public function rules()
	{
		return array(
			// username and password are required
			array('email, password', 'required'),
			array('email', 'email'),
			array('email', 'notAlreadyRegistered'),
			array('password', 'length', 'allowEmpty'=>false),
			array('accept_terms', 'required', 'requiredValue' => 1, 'message' => 'Πρέπει να αποδεχτείτε τους Ορους Χρήσης του ιστότοπου'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function notAlreadyRegistered()
	{
		$user = User::model()->findByAttributes(array('email'=>$this->email));
		if ($user != null)
			$this->addError('email', 'Υπάρχει ήδη εγγεγραμμένος χρήστης με αυτό το email.');
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
			'password'=>'Κωδικός πρόσβασης',
			'accept_terms'=>'Αποδέχομαι τους όρους χρήσης',
			'verifyCode'=>'Κωδικός επιβεβαίωσης',
		);
	}
}
