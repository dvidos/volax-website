<div style="width:33%;float:left;">
	<h2>Posts</h2>
	<ul>
		<li><?php echo CHtml::link('Draft', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_DRAFT)) ?> (<?php echo Post::model()->count('status='.Post::STATUS_DRAFT); ?>)</li>
		<li><?php echo CHtml::link('Published', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_PUBLISHED)) ?> (<?php echo Post::model()->count('status='.Post::STATUS_PUBLISHED); ?>)</li>
		<li><?php echo CHtml::link('Archived', array('/admin/posts/index', 'Post[status]'=>Post::STATUS_ARCHIVED)) ?> (<?php echo Post::model()->count('status='.Post::STATUS_ARCHIVED); ?>)</li>
	</ul>
	<?php echo CHtml::link('Create New Post', array('/admin/posts/create'), array('class'=>'button')); ?>
	<p>&nbsp;</p>
	
	<h2>Σχόλια</h2>
	<ul>
		<li><?php echo CHtml::link('Pending', array('/admin/comments/index', 'status'=>Comment::STATUS_PENDING)) ?> (<?php echo Comment::model()->count('status='.Comment::STATUS_PENDING); ?>)</li>
		<li><?php echo CHtml::link('Approved', array('/admin/comments/index', 'status'=>Comment::STATUS_APPROVED)) ?> (<?php echo Comment::model()->count('status='.Comment::STATUS_APPROVED); ?>)</li>
	</ul>
	
</div><div style="width:33%;float:left;">
	<h2>Κατηγορίες</h2>
	<ul>
	<?php
		function listCateogoriesFor($parent_id)
		{
			$categories = Category::findAllOfParent($parent_id);
			if (count($categories) > 0)
			{
				echo '<ul>';
				foreach ($categories as $category)
				{
					$html = CHtml::link($category->title, array('/admin/categories/update', 'id'=>$category->id));
					$html .= ' (';
					if ($category->postsCount == 0)
						$html .= '0';
					else
						$html .= CHtml::link($category->postsCount, array('/admin/posts/index', 'Post[category_id]'=>$category->id));
					$html .= ')';
					
					echo '<li>' . $html . '</li>';
					listCateogoriesFor($category->id);
				}
				echo '</ul>';
			}
		}

		listCateogoriesFor(0);
	?>
	</ul>
	

</div><div style="width:33%;float:left;">
	<h2>Users</h2>
	
	
	<h2>Ads</h2>
	
	

</div>
<div style="clear:both;"></div>

<p>Volax.gr, software version <?php echo Yii::app()->params['version']; ?>

