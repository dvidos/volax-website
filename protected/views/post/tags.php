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
	
	
	$included = array();
	echo CHtml::tag('p', array('id'=>'tags-list'), showTags($tags, '/^[ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρσςτυφχψωΆΈΊΌΎΉΏάέύίόήώ].+$/u', $included));
	echo CHtml::tag('p', array('id'=>'tags-list'), showTags($tags, '/^[A-ZA-z].*$/', $included));
	echo CHtml::tag('p', array('id'=>'tags-list'), showTags($tags, '', $included));
	
	
?>
