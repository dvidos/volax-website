<?php

class StringTools extends CApplicationComponent
{
	public function urlFriendly($text)
	{
		$gr = array(
				'α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω', 
				'Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 
				'Έ', 'Ύ', 'Ί', 'Ό', 'Ά', 'Ή', 'Ώ', 'έ', 'ύ', 'ί', 'ό', 'ά', 'ή', 'ώ', 'ϋ', 'ϊ', 'Ϋ', 'Ϊ', 'ΰ', 'ΐ', 'ς');
		$en = array(
				'a', 'b', 'g', 'd', 'e', 'z', 'h', 'th','i', 'k', 'l', 'm', 'n', 'x', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x', 'ps','o', 
				'A', 'B', 'G', 'D', 'E', 'Z', 'H', 'TH','I', 'K', 'L', 'M', 'N', 'X', 'O', 'P', 'R', 'S', 'T', 'Y', 'F', 'X', 'PS','O', 
				'E', 'Y', 'I', 'O', 'A', 'H', 'O', 'e', 'y', 'i', 'o', 'a', 'h', 'o', 'u', 'i', 'Y', 'I', 'u', 'i', 's');
		
		$text = str_replace(' ', '-', $text);
		$text = str_replace($gr, $en, $text);
		$text = preg_replace('/[^a-zA-Z0-9_\-]/', '', $text);
		$text = strtolower($text);
		
		return $text;
	}	
	
	public function friendlySize($size)
	{
		$kb = 1024;
		$mb = 1048576;
		
		if ($size == 0)
		{
			return '0';
		}
		else if ($size < $kb)
		{
			return '1 KB';
		}
		else if ($size < $mb)
		{
			$s = $size / $kb;
			return round($s, ($s < 10) ? 1 : 0) . ' KB';
		}
		else
		{
			$s = $size / $mb;
			return round($s, ($s < 10) ? 1 : 0) . ' MB';
		}
	}
}

