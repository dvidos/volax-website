
<h2>Κύριες Στήλες</h2>
<?php
	function categoryCell($parent_category, $categories)
	{
		$html = '';
		$html .= '<h3 style="font-size: 110%; font-weight: bold; margin: 0 0 .75em 0;">' . CHtml::encode($parent_category->title) . '</h3>';
		
		$html .= '<table class="compact">';
		for ($i = 0; $i < count($categories); $i++)
		{
			if ($categories[$i]->parent_id != $parent_category->id)
				continue;
			$category = $categories[$i];
			
			$html .= '<tr><td style="width: 80%; border:none;">';
			$html .= CHtml::link(CHtml::encode($category->title), 
				array('/admin/posts/index', 'Post[category_id]'=>$category->id), 
				array('style'=>'font-weight: normal;'));
			$html .= '</td><td style="width: 20%; border:none; text-align:right;">';
			$color = ($category->postsCount == 0) ? '#c00' : '#999';
			$html .= '<span style="color: '.$color.';">' . $category->postsCount . '</span>';
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		
		return $html;
	}

	$grandpa = 3;
	$categories = Category::model()->findAll(array(
		'order'=>'parent_id, view_order',
		'with'=>'postsCount',
	));
	$cells = 0;
	$max_cols = 4;
	echo '<table class="bordered"><tr>';
	foreach ($categories as $category)
	{
		if ($category->parent_id != $grandpa)
			continue;
	
		echo '<td width="10%" style="font-size: 90%;">';
		echo categoryCell($category, $categories);
		echo '</td>';
		
		if (++$cells >= $max_cols)
		{
			echo '</tr><tr>';
			$cells = 0;
		}
	}
	while (++$cells <= $max_cols)
		echo '<td>&nbsp;</td>';
	echo '</tr></table>';
?>


<h2>Βοηθητικές κατηγορίες</h2>
<?php
	function additionalCategory($id)
	{
		$category = Category::model()->findByPk($id);
		if ($category == null)
			throw new Exception('Fatal! Category '. $id . ' not found!');
		
		$html = '';
		$html .= '<tr><td style="width: 80%; border:none;">';
		$html .= CHtml::link(CHtml::encode($category->title), 
			array('/admin/posts/index', 'Post[category_id]'=>$category->id), 
			array('style'=>'font-weight: normal;'));
		$html .= '</td><td style="width: 20%; border:none; text-align:right;">';
		$color = ($category->postsCount == 0) ? '#c00' : '#999';
		$html .= '<span style="color: '.$color.';">' . $category->postsCount . '</span>';
		$html .= '</td></tr>';
		return $html;
	}
	
	echo '<table class="compact" style="width: 25%; font-size: 100%;">';
	echo additionalCategory(124);
	echo additionalCategory(19);
	echo additionalCategory(18);
	echo '</table>';
	
?>
