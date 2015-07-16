<?php
	function postLink($post)
	{
		$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
		$tooltip = ($post->author == null ? '(χρήστης #'.$post->author_id.')' : $post->author->fullname) . ' στις ' . $post->friendlyCreateTime;
		
		$html = '';
		$html .= CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id), array('title'=>'Διόρθωση', 'style'=>'font-weight:normal; '));
		$html .= '&nbsp;&nbsp;&nbsp;';
		$html .= CHtml::link('(Εμφ)', array('/post/view', 'id'=>$post->id, 'title'=>$post->title), array('target'=>'_blank', 'title'=>$tooltip, 'style'=>'font-weight:normal; color: #aaa;'));
		
		return $html;
	}
?>

<table><tr><td width="20%">
	
	<h2>Στατιστικά</h2>
	<table class="compact">
		<tr><td>Αναρτήσεις</td><td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED); ?></td></tr>
		<tr><td>Πρόχειρες</td><td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_DRAFT); ?></td></tr>
		<tr><td>Αρχειοθετημένες</td><td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED); ?></td></tr>
		<tr><td>Κατηγορίες</td><td style="text-align:right;"><?php echo Category::model()->count('status='.Category::STATUS_PUBLISHED); ?></td></tr>
		<tr><td>Σχόλια</td><td style="text-align:right;"><?php echo Comment::model()->count('status='.Comment::STATUS_APPROVED); ?></td></tr>
		<tr><td>Χρήστες</td><td style="text-align:right;"><?php echo User::model()->count(); ?></td></tr>
	</table>
	
	<h2>Οδηγίες</h2>
	<?php 
		echo CHtml::link('Για διαχειριστές', array('dashboard/viewPage', 'url_keyword'=>'adminNotes'), array('style'=>'font-weight:normal;')) . '<br />';
		echo CHtml::link('Για συντάκτες', array('dashboard/viewPage', 'url_keyword'=>'editorNotes'), array('style'=>'font-weight:normal;')) . '<br />';
		echo CHtml::link('Μακροεντολές', array('dashboard/viewPage', 'url_keyword'=>'shortcodes'), array('style'=>'font-weight:normal;')) . '<br />';
	?>
		
</td><td width="1%">&nbsp;</td><td width="40%">
	

	<h2>Δημόσιες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'limit'=>8,
		));
		foreach ($posts as $post)
			echo postLink($post) . '<br />';
	?></p>
	
	<h2>Βοηθητικές κατηγορίες</h2>
	<p>
		<?php echo CHtml::link('HELLO! (blog)', array('/admin/posts/index', 'Post[category_id]'=>124), array('style'=>'font-weight:normal;')); ?><br />
		<?php echo CHtml::link('Του Χωριού', array('/admin/posts/index', 'Post[category_id]'=>19), array('style'=>'font-weight:normal;')); ?><br />
		<?php echo CHtml::link('Του Συλλόγου', array('/admin/posts/index', 'Post[category_id]'=>18), array('style'=>'font-weight:normal;')); ?><br />
	</p>
	
	
</td><td width="40%">
	
	<h2>Πρόχειρες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT,
			'order'=>'update_time DESC',
			'limit'=>8,
		));
		foreach ($posts as $post)
			echo postLink($post) . '<br />';
	?></p>
	
	<h2>Αλλα</h2>
	<p>
		<?php echo CHtml::link('Σελίδες του παλιού volax-tinos.gr', Yii::app()->baseUrl . '/old-volax-tinos-gr', array('target'=>'_blank', 'style'=>'font-weight:normal;')); ?><br />
		<?php echo CHtml::link('Διαμοιραζόμενο βιβλίο excel', 'https://docs.google.com/spreadsheets/d/16zreBYooHHAdZC7MhD-IX-iPBzkRpG8IGsA7ZcAWbfA/edit?usp=sharing', array('target'=>'_blank', 'style'=>'font-weight:normal;')); ?><br />
	</p>
		
</td></tr></table>


<h2>Κύριες Στήλες</h2>
<table class="bordered"><tr>
<?php
	function categoryCell($parent_category, $categories)
	{
		echo '<td width="10%" style="font-size: 80%;">';
		echo '<h3 style="font-size: 110%; font-weight: bold; margin: 0 0 .75em 0;">' . CHtml::encode($parent_category->title) . '</h3>';
		for ($i = 0; $i < count($categories); $i++)
		{
			if ($categories[$i]->parent_id != $parent_category->id)
				continue;
			$category = $categories[$i];
			
			echo CHtml::link(
				str_replace(' ', '&nbsp;', CHtml::encode($category->title)), 
				array('/admin/posts/index', 'Post[category_id]'=>$category->id), 
				array('style'=>'font-weight: normal;'));
			echo '&nbsp;&nbsp;';
			$color = ($category->postsCount == 0) ? '#c00' : '#999';
			echo '<span style="color: '.$color.';">' . $category->postsCount . '</span>';
			echo '<br />';
		}
		echo '</td>';
	}
	
	$grandpa = 3;
	$categories = Category::model()->findAll(array(
		'order'=>'parent_id, view_order',
		'with'=>'postsCount',
	));
	$cells = 0;
	$max_cols = 6;
	foreach ($categories as $category)
	{
		if ($category->parent_id != $grandpa)
			continue;
	
		categoryCell($category, $categories);
		if (++$cells >= $max_cols)
		{
			echo '</tr><tr>';
			$cells = 0;
		}
	}
	while (++$cells <= $max_cols)
	{
		echo '<td>&nbsp;</td>';
	}
?>
</tr></table>



<h2>Πρόσφατη δραστηριότητα</h2>
<table class="compact">
<?php
	$actions = PostRevision::model()->findAll(array(
		'select'=>'id, post_id, `datetime`, user_id, was_deleted',
		'order'=>'`datetime` DESC',
		'limit'=>30,
	));
	$presented = array();
	foreach ($actions as $action)
	{
		$key = $action->user_id . '-' . $action->post_id;
		if (in_array($key, $presented))
			continue;
		$presented[] = $key;
		if (count($presented) > 10)
			break;
		
		$greek_datetime = substr($action->datetime, 8, 2) . '-' . substr($action->datetime, 5, 2) . '-' . substr($action->datetime, 2, 2) . ',&nbsp;' . substr($action->datetime, 11, 5);
		echo '<tr>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . $greek_datetime . '</td>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . ($action->user == null ? '(χρήστης&nbsp;#'.$action->user_id.')' : str_replace(' ', '&nbsp;', $action->user->fullname)) . '</td>';
		echo '<td style="white-space: nowrap; padding-right: 2em;">' . ($action->was_deleted ? 'Διέγραψε' : 'Διόρθωσε') . '</td>';
		echo '<td>' . ($action->post == null ? '(ανάρτηση #'.$action->post_id.')' : postLink($action->post)) . '</td>';
		echo '</tr>';
	}
?>
</table>



