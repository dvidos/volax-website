<?php
	$pageTitle = 'Tags';
?>

<h1>Tags</h1>

<?php 
	function showTag($name, $freq)
	{
		return 
			CHtml::link(CHtml::encode($name), array('post/list', 'tag'=>$name)) .
			'&nbsp;(' . $freq . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
	}
	
	function showTags($tags, $regExp, &$included)
	{
		$html = '';
		
		foreach ($tags as $tag)
		{
			if (in_array($tag->name, $included))
				continue;
			
			if (!empty($regExp))
				if (!preg_match($regExp, $tag->name))
					continue;
			
			$html .= showTag($tag->name, $tag->frequency) . "\n";
			$included[] = $tag->name;
		}
		
		return $html;
	}
	
	function showTagsGroup($anchor, $title, $tags, $regExp, &$included)
	{
		$html = '';
		$html .= '<a name="'.$anchor.'" id="'.$anchor.'"></a> ';
		$html .= '<h3>' . $title . '</h3>';
		$html .= "\r\n";
		
		foreach ($tags as $tag)
		{
			if (in_array($tag->name, $included))
				continue;
			
			if (!empty($regExp))
				if (!preg_match($regExp, $tag->name))
					continue;
			
			$html .= showTag($tag->name, $tag->frequency) . "\n";
			$included[] = $tag->name;
		}
		
		
		
	}

	function constructGroup($tags, $group, &$header, &$body)
	{
		$found = false;
		$groupHeader = '<a href="#'.$group['anchor'].'"><strong>&nbsp;'.CHtml::encode($group['caption']).'&nbsp;</strong></a> &nbsp; ';
		$groupBody = '<a id="'.$group['anchor'].'"></a><h2>'.CHtml::encode($group['caption']).'</h2><p id="tags-list">';
		
		foreach ($tags as $tag)
		{
			if (!preg_match($group['re'], $tag->name))
				continue;
			
			$groupBody .= CHtml::link(CHtml::encode($tag->name), array('post/list', 'tag'=>$tag->name)) . '&nbsp;(' . $tag->frequency . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
			$found = true;
		}
		$groupBody .= '</p>';
		
		if ($found)
		{
			$header .= $groupHeader;
			$body .= $groupBody;
		}
	}
	
	$groups = array(
		array('caption'=>'Α', 'anchor'=>'gr_alpha', 're'=> '/^[ΑαΆά].+$/u'),
		array('caption'=>'Β', 'anchor'=>'gr_beta', 're'=> '/^[Ββ].+$/u'),
		array('caption'=>'Γ', 'anchor'=>'gr_gamma', 're'=> '/^[Γγ].+$/u'),
		array('caption'=>'Δ', 'anchor'=>'gr_delta', 're'=> '/^[Δδ].+$/u'),
		array('caption'=>'Ε', 'anchor'=>'gr_epsilon', 're'=> '/^[ΕεΈέ].+$/u'),
		array('caption'=>'Ζ', 'anchor'=>'gr_zeta', 're'=> '/^[Ζζ].+$/u'),
		array('caption'=>'Η', 'anchor'=>'gr_eta', 're'=> '/^[ΗηΉή].+$/u'),
		array('caption'=>'Θ', 'anchor'=>'gr_theta', 're'=> '/^[Θθ].+$/u'),
		array('caption'=>'Ι', 'anchor'=>'gr_iota', 're'=> '/^[ΙιΊίΐ].+$/u'),
		array('caption'=>'Κ', 'anchor'=>'gr_kappa', 're'=> '/^[Κκ].+$/u'),
		array('caption'=>'Λ', 'anchor'=>'gr_lamda', 're'=> '/^[Λλ].+$/u'),
		array('caption'=>'Μ', 'anchor'=>'gr_mi', 're'=> '/^[Μμ].+$/u'),
		array('caption'=>'Ν', 'anchor'=>'gr_ni', 're'=> '/^[Νν].+$/u'),
		array('caption'=>'Ξ', 'anchor'=>'gr_xi', 're'=> '/^[Ξξ].+$/u'),
		array('caption'=>'Ο', 'anchor'=>'gr_omicron', 're'=> '/^[ΟοΌό].+$/u'),
		array('caption'=>'Π', 'anchor'=>'gr_pi', 're'=> '/^[Ππ].+$/u'),
		array('caption'=>'Ρ', 'anchor'=>'gr_ro', 're'=> '/^[Ρρ].+$/u'),
		array('caption'=>'Σ', 'anchor'=>'gr_sigma', 're'=> '/^[Σσ].+$/u'),
		array('caption'=>'Τ', 'anchor'=>'gr_tau', 're'=> '/^[Ττ].+$/u'),
		array('caption'=>'Υ', 'anchor'=>'gr_ypsilon', 're'=> '/^[ΥυΎύΰ].+$/u'),
		array('caption'=>'Φ', 'anchor'=>'gr_fi', 're'=> '/^[Φφ].+$/u'),
		array('caption'=>'Χ', 'anchor'=>'gr_hi', 're'=> '/^[Χχ].+$/u'),
		array('caption'=>'Ψ', 'anchor'=>'gr_psi', 're'=> '/^[Ψψ].+$/u'),
		array('caption'=>'Ω', 'anchor'=>'gr_omega', 're'=> '/^[ΩωΏώ].+$/u'),
		array('caption'=>'En', 'anchor'=>'english', 're'=> '/^[A-ZA-z].*$/'),
		array('caption'=>'123', 'anchor'=>'numbers', 're'=> '/^[0-9].*$/'),
	);
	
	$header = '';
	$body = '';
	foreach ($groups as $group)
		constructGroup($tags, $group, $header, $body);
	
	echo '<p id="tags-list">' . $header . '</p>';
	echo $body;

	
?>
