
<table>
<tr>
	<td width="25%">
	
	
		<h2>Αναρτήσεις</h2>
		<table class="compact">
			<tr>
				<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/paste.png'); ?>
				<td><?php echo CHtml::link('Πρόχειρες', array('/author/posts/index', 'Post[status]'=>Post::STATUS_DRAFT)) ?></td>
				<td><?php echo Post::model()->count('status='.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id); ?></td>
			</tr>
			<tr>
				<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/ok.png'); ?>
				<td><?php echo CHtml::link('Δημοσιευμένες', array('/author/posts/index', 'Post[status]'=>Post::STATUS_PUBLISHED)) ?></td>
				<td><?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED.' AND author_id='.Yii::app()->user->id); ?></td>
			</tr>
			<tr>
				<td><?php echo CHtml::image(Yii::app()->baseUrl . '/assets/images/actions/multiple.png'); ?>
				<td><?php echo CHtml::link('Αρχειοθετημένες', array('/author/posts/index', 'Post[status]'=>Post::STATUS_ARCHIVED)) ?></td>
				<td><?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED.' AND author_id='.Yii::app()->user->id); ?></td>
			</tr>
			<tr><td colspan="3">
				<?php echo CHtml::link('Νέα ανάρτηση', array('/author/posts/create'), array(
					'class'=>'button', 
					'style'=>'margin-top: .5em; text-align: center;'
				)); ?>
			</td></tr>
		</table>
		
		
	</td>
	<td width="25%">

	
		<h2>Πρόχειρες</h2>
		<?php 
			$posts = Post::model()->findAll(array(
				'condition'=>'status='.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id,
				'order'=>'update_time DESC',
				'limit'=>4,
			));
			echo '<ul>';
			foreach ($posts as $post)
			{
				echo '<li>' . CHtml::link($post->title, array('/author/posts/update', 'id'=>$post->id)) . '</li>';
			}
			echo '</ul>';
		?>
	
		
		
	</td>
	<td width="25%">
	
	
		<h2>Πρόσφατες</h2>
		<?php 
			$posts = Post::model()->findAll(array(
				'condition'=>'status<>'.Post::STATUS_DRAFT.' AND author_id='.Yii::app()->user->id,
				'order'=>'update_time DESC',
				'limit'=>4,
			));
			echo '<ul>';
			foreach ($posts as $post)
			{
				echo '<li>' . CHtml::link($post->title, array('/author/posts/update', 'id'=>$post->id)) . '</li>';
			}
			echo '</ul>';
		?>
	
	
	</td>
	<td width="25%">
		
		
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
						$c1 .= '-';
					for ($i = 0; $i < $depth; $i++)
						$c1 .= '&nbsp';
					$c1 .= CHtml::encode($category->title);
					
					$count = Post::model()->count('category_id='.$category->id.' AND author_id='.Yii::app()->user->id);
					if ($count == 0)
						$c2 = '0';
					else
						$c2 = CHtml::link($count, array('/author/posts/index', 'Post[category_id]'=>$category->id));
						
					echo '<tr><td>' . $c1 . '</td><td>' . $c2 . '</td></tr>' . "\r\n";
					listCategoriesFor($category->id, $depth + 4);
				}
			}

			echo '<table class="compact">';
			listCategoriesFor(0, 0);
			echo '</table>';
		?>
		</ul>

		
		
	</td>
</tr>
</table>


<p>Volax.gr, software version <?php echo Yii::app()->params['version']; ?>

