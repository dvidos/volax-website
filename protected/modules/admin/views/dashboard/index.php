
<table><tr><td width="20%">
	

	<h2>Αναρτήσεις</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/paste.png'); ?>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_DRAFT)) ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_DRAFT); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/ok.png'); ?>
			<td><?php echo CHtml::link('Δημοσιευμένες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_PUBLISHED)) ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/multiple.png'); ?>
			<td><?php echo CHtml::link('Αρχειοθετημένες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_ARCHIVED)) ?></td>
			<td style="text-align:right;"><?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED); ?></td>
		</tr>
	</table>
	
	<h2>Σχόλια</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/who.png'); ?>
			<td><?php echo CHtml::link('Εκκρεμή', array('/admin/comments/index', 'status'=>Comment::STATUS_PENDING)) ?></td>
			<td style="text-align:right;"><?php echo Comment::model()->count('status='.Comment::STATUS_PENDING); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/user.png'); ?>
			<td><?php echo CHtml::link('Εγκεκριμένα', array('/admin/comments/index', 'status'=>Comment::STATUS_APPROVED)) ?></td>
			<td style="text-align:right;"><?php echo Comment::model()->count('status='.Comment::STATUS_APPROVED); ?></td>
		</tr>
	</table>
	
	<h2>Κατηγορίες</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/paste.png'); ?>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/categories/index', 'Category[status]'=>Category::STATUS_DRAFT)) ?></td>
			<td style="text-align:right;"><?php echo Category::model()->count('status='.Category::STATUS_DRAFT); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/ok.png'); ?>
			<td><?php echo CHtml::link('Δημοσιευμένες', array('/admin/categories/index', 'Category[status]'=>Category::STATUS_PUBLISHED)) ?></td>
			<td style="text-align:right;"><?php echo Category::model()->count('status='.Category::STATUS_PUBLISHED); ?></td>
		</tr>
	</table>
	
	<h2>Οδηγίες</h2>
	<?php 
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'Για διαχειριστές', 'url'=>array('dashboard/viewPage', 'url_keyword'=>'adminNotes')),
				array('label'=>'Για συντάκτες', 'url'=>array('dashboard/viewPage', 'url_keyword'=>'editorNotes')),
				array('label'=>'Μακροεντολές', 'url'=>array('dashboard/viewPage', 'url_keyword'=>'shortcodes')),
			),
		));
	?>
		
		
</td><td width="1%">&nbsp;</td><td width="40%">
	

	<h2>Πρόσφατες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			//'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'create_time DESC',
			'limit'=>5,
		));
		//echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			//echo '<li>';
			echo CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id));
			if ($post->author != null)
				echo ' &nbsp; (' . CHtml::link($post->author->username, array('/admin/posts', 'Post[author_id]'=>$post->author_id), array('style'=>'color:#aaa')) . ')';
			echo '<br>';
			//echo '</li>';
		}
		//echo '</ul>';
	?></p>
	
	<h2>Πρόσφατα διορθωμένες αναρτήσεις</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			//'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'limit'=>5,
		));
		//echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			//echo '<li>';
			echo CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id));
			if ($post->author != null)
				echo ' &nbsp; (' . CHtml::link($post->author->username, array('/admin/posts', 'Post[author_id]'=>$post->author_id), array('style'=>'color:#aaa')) . ')';
			//echo '</li>';
			echo '<br>';
		}
		//echo '</ul>';
	?></p>
	
	<h2>Drafts</h2>
	<p><?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT,
			'order'=>'update_time DESC',
			'limit'=>5,
		));
		//echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			//echo '<li>';
			echo CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id));
			if ($post->author != null)
				echo ' &nbsp; (' . CHtml::link($post->author->username, array('/admin/posts', 'Post[author_id]'=>$post->author_id), array('style'=>'color:#aaa')) . ')';
			echo '<br>';
			//echo '</li>';
		}
		//echo '</ul>';
	?></p>
	
		
</td><td width="40%">
	
	
	<h2>Διάρθρωση</h2>
	<ul>
	<?php
		function listCategoriesFor($parent_id, $depth)
		{
			$categories = Category::findAllOfParent($parent_id);
			foreach ($categories as $category)
			{
				$c1 = '';
				$c2 = '';
				
				if ($depth > 0)
				{
					$c1 .= '';
					for ($i = 0; $i < $depth; $i++)
						$c1 .= '.&nbsp;&nbsp;&nbsp;.&nbsp;&nbsp;&nbsp;';
				}
				$c1 .= CHtml::link($category->title, array('/admin/categories/update', 'id'=>$category->id));

				if ($category->postsCount == 0)
					$c2 = '0';
				else
					$c2 = CHtml::link($category->postsCount, array('/admin/posts/index', 'Post[category_id]'=>$category->id));
					
				echo '<tr><td>' . $c1 . '</td><td>' . $c2 . '</td></tr>' . "\r\n";
				listCategoriesFor($category->id, $depth + 1);
			}
		}

		echo '<table class="compact">';
		listCategoriesFor(0, 0);
		echo '</table>';
	?>
	</ul>

		
		
</td></tr></table>



