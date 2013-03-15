<?php

Yii::import('zii.widgets.CPortlet');

class AdminMenu extends CPortlet
{
	public function init()
	{
		$this->title='Admin Quick Menu';
		parent::init();
	}

	protected function renderContent()
	{
		$this->render('adminMenu');
	}
}