<?php

class ForgotPasswordFormModel extends CFormModel
{
	public $email;
	public $verifyCode;

	public function rules()
	{
		return array(
			// username and password are required
			array('email', 'required'),
			array('email', 'email'),
			array('email', 'emailExists'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function emailExists()
	{
		$user = User::model()->findByAttributes(array('email'=>$this->email));
		if ($user == null)
			$this->addError('email', 'Δεν βρέθηκε χρήστης με αυτό το email στα αρχεία μας.');
	}
	
	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
			'verifyCode'=>'Κωδικός επιβεβαίωσης',
		);
	}
}
