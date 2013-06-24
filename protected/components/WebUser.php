<?php

class WebUser extends CWebUser
{
	private $_user;

	function getIsAdmin()
	{
		if ($this->isGuest)
			return false;
		
		$user = $this->getUser();
		if ($user == null)
			return false;
		
		return intval($user->is_admin) == 1;
	}

	function getIsAuthor()
	{
		if ($this->isGuest)
			return false;
		
		$user = $this->getUser();
		if ($user == null)
			return false;
		
		return intval($user->is_author) == 1;
	}

	protected function getUser()
	{
		if ($this->_user === null)
		{
			$this->_user = User::model()->findByPk(Yii::app()->user->id);
		}
		
		return $this->_user;
	}
}


?>