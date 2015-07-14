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
		{
			// we try to fix the value automatically for example "toπωnymia" to "τοπωνυμια".
			$greek = $this->forceGreekCapitals2($value);
			$object->$attribute = $greek;
			Yii::log('Το "' . mb_strtolower($value, 'utf-8') . '" μετατράπηκε σε "' . mb_strtolower($greek, 'utf-8') . '" (utf-8 encoding)', 'warning');
			
			// check again
			$value = $object->$attribute;
			if (preg_match($this->regexp, $value))
				$this->addError($object, $attribute, 'Βρέθηκαν λέξεις με μεικτά ελληνικά-αγγλικά (' . mb_strtolower($value, 'utf-8') . ')');
			}
	}
	
	function forceGreekCapitals2($value)
	{
		$en = array('E', 'T', 'Y', 'I', 'O', 'P', 'A', 'H', 'K', 'Z', 'X', 'B', 'N', 'M');
		$gr = array('Ε', 'Τ', 'Υ', 'Ι', 'Ο', 'Ρ', 'Α', 'Η', 'Κ', 'Ζ', 'Χ', 'Β', 'Ν', 'Μ');
		return str_replace($en, $gr, $value);
	}
}

