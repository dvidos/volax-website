
<table><tr><td width="33%">
	

	<h2>Αναρτήσεις</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/paste.png'); ?>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_DRAFT)) ?></td>
			<td><?php echo Post::model()->count('status='.Post::STATUS_DRAFT); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/ok.png'); ?>
			<td><?php echo CHtml::link('Δημοσιευμένες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_PUBLISHED)) ?></td>
			<td><?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/multiple.png'); ?>
			<td><?php echo CHtml::link('Αρχειοθετημένες', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_ARCHIVED)) ?></td>
			<td><?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED); ?></td>
		</tr>
		<tr><td colspan="3">
			<?php echo CHtml::link('Νέα ανάρτηση', array('/admin/posts/create'), array(
				'class'=>'button', 
				'style'=>'margin-top: .5em; text-align: center;'
			)); ?>
		</td></tr>
	</table>
	
	<h2>Σχόλια</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/who.png'); ?>
			<td><?php echo CHtml::link('Εκκρεμή', array('/admin/comments/index', 'status'=>Comment::STATUS_PENDING)) ?></td>
			<td><?php echo Comment::model()->count('status='.Comment::STATUS_PENDING); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/user.png'); ?>
			<td><?php echo CHtml::link('Εγκεκριμένα', array('/admin/comments/index', 'status'=>Comment::STATUS_APPROVED)) ?></td>
			<td><?php echo Comment::model()->count('status='.Comment::STATUS_APPROVED); ?></td>
		</tr>
	</table>
	
	<h2>Κατηγορίες</h2>
	<table class="compact">
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/paste.png'); ?>
			<td><?php echo CHtml::link('Πρόχειρες', array('/admin/categories/index', 'Category[status]'=>Category::STATUS_DRAFT)) ?></td>
			<td><?php echo Category::model()->count('status='.Category::STATUS_DRAFT); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/ok.png'); ?>
			<td><?php echo CHtml::link('Δημοσιευμένες', array('/admin/categories/index', 'Category[status]'=>Category::STATUS_PUBLISHED)) ?></td>
			<td><?php echo Category::model()->count('status='.Category::STATUS_PUBLISHED); ?></td>
		</tr>
	</table>
		
		
</td><td width="33%">
	

	<h2>Πρόσφατες αναρτήσεις</h2>
	<?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status<>'.Post::STATUS_DRAFT,
			'order'=>'update_time DESC',
			'limit'=>5,
		));
		echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			echo '<li>' . CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id)) . '</li>';
		}
		echo '</ul>';
	?>
	
	<h2>Drafts</h2>
	<?php 
		$posts = Post::model()->findAll(array(
			'condition'=>'status='.Post::STATUS_DRAFT,
			'order'=>'update_time DESC',
			'limit'=>5,
		));
		echo '<ul>';
		foreach ($posts as $post)
		{
			$title = ($post->title == '') ? '#' . $post->id . ' (χωρίς τίτλo)' : $post->title;
			echo '<li>' . CHtml::link($title, array('/admin/posts/update', 'id'=>$post->id)) . '</li>';
		}
		echo '</ul>';
	?>
	
		
</td><td width="33%">
	
	
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
						$c1 .= '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
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



