

<?php
	function postLink($post)
	{
		$title = ($post->title == '') ? '(#' . $post->id . ', χωρίς τίτλo)' : $post->title;
		$html = '';
		$html .= CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id), array('title'=>'Διόρθωση', 'style'=>'font-weight:normal; '));
		if ($post->author != null && !empty($post->author->initials))
			$html .= '&nbsp;&nbsp;&nbsp;<span style="color:#aaa;">' . $post->author->initials . '</span>';
		
		return $html;
	}
?>

<table><tr><td width="40%">
	

	<h2>Πρόσφατες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'limit'=>15,
		));
		if (count($posts) == 0)
			echo '<p>Δεν βρέθηκαν αναρτήσεις</p>';
		else
		{
			foreach ($posts as $post)
				echo postLink($post) . '<br />';
			echo CHtml::link('Ολες...', array('/admin/posts', 'Post[status]'=>Post::STATUS_PUBLISHED), array('style'=>'font-weight:normal;')) . '<br />';
		}
	?></p>
	
</td><td width="40%">
	
	<h2>Πρόχειρες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT,
			'order'=>'update_time DESC',
			'limit'=>15,
		));
		if (count($posts) == 0)
			echo '<p>Δεν βρέθηκαν αναρτήσεις</p>';
		else
		{
			foreach ($posts as $post)
				echo postLink($post) . '<br />';
			echo CHtml::link('Ολες...', array('/admin/posts', 'Post[status]'=>Post::STATUS_DRAFT), array('style'=>'font-weight:normal;')) . '<br />';
		}
	?></p>
		
</td><td width="1%">&nbsp;</td><td width="20%">
	
	<h2>Σύνολα</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::link('Δημ. αναρτήσεις', array('/admin/posts', 'Post[status]'=>Post::STATUS_PUBLISHED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/posts', 'Post[status]'=>Post::STATUS_DRAFT), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_DRAFT); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Αρχειοθετημένες', array('/admin/posts', 'Post[status]'=>Post::STATUS_ARCHIVED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Κατηγορίες', array('/admin/categories', 'Category[status]'=>Category::STATUS_PUBLISHED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Category::model()->count('status='.Category::STATUS_PUBLISHED); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Σχόλια', array('/admin/comments', 'Comment[status]'=>Comment::STATUS_APPROVED), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo Comment::model()->count('status='.Comment::STATUS_APPROVED); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Γεωδαιτικά', array('/admin/geoFeatures'), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo GeoFeature::model()->count(); ?></td>
		</tr><tr>
			<td><?php echo CHtml::link('Χρήστες', array('/admin/users'), array('style'=>'font-weight:normal;')); ?></td>
			<td style="text-align:right;"><?php echo User::model()->count(); ?></td>
		</tr>
	</table>
	
</td></tr>
<!--------------------------------------------------------------------->
<tr><td>
	

	<h2>Οδηγίες</h2>
	<?php 
		if (Yii::app()->user->isAdmin)
			echo CHtml::link('Για διαχειριστές', array('dashboard/viewPage', 'url_keyword'=>'adminNotes'), array('style'=>'font-weight:normal;')) . '<br />';
		echo CHtml::link('Για συντάκτες', array('dashboard/viewPage', 'url_keyword'=>'editorNotes'), array('style'=>'font-weight:normal;')) . '<br />';
		echo CHtml::link('Μακροεντολές', array('dashboard/viewPage', 'url_keyword'=>'shortcodes'), array('style'=>'font-weight:normal;')) . '<br />';
	?>
	
	
</td><td>
	
	<h2>Αλλα</h2>
	<p>
		<?php echo CHtml::link('Email λίστες ανακοινώσεων', 'http://lists.volax.gr/mailman/admin', array('target'=>'_blank', 'style'=>'font-weight:normal;')); ?>
		<span style="font-weight: bold; color:#c00;">(νέο)</span><br />
		<?php echo CHtml::link('Στατιστικά Google Analytics', 'https://www.google.com/analytics/', array('target'=>'_blank', 'style'=>'font-weight:normal;')); ?><br />
		<?php echo CHtml::link('Διαμοιραζόμενο βιβλίο excel', 'https://docs.google.com/spreadsheets/d/16zreBYooHHAdZC7MhD-IX-iPBzkRpG8IGsA7ZcAWbfA/edit?usp=sharing', array('target'=>'_blank', 'style'=>'font-weight:normal;')); ?><br />
	</p>
		
</td><td>&nbsp;</td><td>

	&nbsp;
	
</td></tr>
</table>

