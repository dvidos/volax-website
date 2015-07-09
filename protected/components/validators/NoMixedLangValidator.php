<?php

class NoMixedLangValidator extends CValidator
{
	var $greek;
	var $regexp;
	
	function __construct()
	{
		$this->greek = 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρστυφχψωςάέύίόήώΆΈΎΊΌΉΏΐΰ';
		$this->regexp = '/([a-zA-Z]['.$this->greek.']|['.$this->greek.'][a-zA-Z])/uS';
	}
	
	function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;
		if (preg_match($this->regexp, $value))
			$this->addError($object, $attribute, 'Βρέθηκαν λέξεις με μεικτά ελληνικά-αγγλικά (' . mb_strtolower($value, 'utf-8') . ')');
	}
}

