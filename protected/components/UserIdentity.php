<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = $this->findUser();
		if ($user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		
		else if (!$user->validatePassword($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		
		else
		{
			$this->_id = $user->id;
			$this->username = $user->email;
			$this->errorCode = self::ERROR_NONE;
		}
		
		return ($this->errorCode == self::ERROR_NONE);
	}
	
	/**
	 * Authenticates a user when he confirms his email.
	 * Special in the sense that we do not know his cleartext password
	 */
	public function authenticateWithoutPassword()
	{
		$user = $this->findUser();
		if ($user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		
		else
		{
			// we do not validate password.
			$this->_id = $user->id;
			$this->username = $user->email;
			$this->errorCode = self::ERROR_NONE;
		}
		
		return ($this->errorCode == self::ERROR_NONE);
	}
	
	
	function findUser()
	{
		// first, try to find a unique email address (usually the case)
		$users = User::model()->findAll('LOWER(email)=?', array(strtolower($this->username)));
		if (count($users) == 1)
			return $users[0];

		// for fallback, try a unique user name... (we have three for jimel with the same email)
		$users = User::model()->findAll('LOWER(username)=?', array(strtolower($this->username)));
		if (count($users) == 1)
			return $users[0];
		
		// finally, give up!
		return null;
	}
	
	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}