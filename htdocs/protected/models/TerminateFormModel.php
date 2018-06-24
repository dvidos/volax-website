<?php

class TerminateFormModel extends CFormModel
{
	public $verify;

	public function rules()
	{
		return array(
			array('verify', 'required', 'requiredValue' => 1, 'message' => 'Πρέπει να επιβεβαιώσετε τον τερματισμό του λογαριασμού'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'verify'=>'Θέλω να τερματίσω τον λογαριασμό μου',
		);
	}
}
