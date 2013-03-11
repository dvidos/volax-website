<?php $this->beginContent('/layouts/main'); ?>

	<div id="content-col1" style="float:left;width:24%;margin-right:1%;">
		<?php   
			$cat = Category::model()->findByPk(3);
			foreach ($cat->subcategories as $category)
			{
				$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
				echo $link;
			}
		?>
	</div>
	<div id="content-col2" style="float:left;width:50%;">
		<?php echo $content; ?>
	</div>
	<div id="content-col4" style="float:left;width:24%;margin-left:1%;">
		<?php
			$cat = Category::model()->findByPk(12);
			foreach ($cat->subcategories as $category)
			{
				$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
				echo $link;
			}

			echo '<p>Οι σελίδες</p>';
			$cat = Category::model()->findByPk(17);
			foreach ($cat->subcategories as $category)
			{
				$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
				echo $link;
			}
		?>
	</div>
	<div style="clear:both;"></div>

<?php $this->endContent(); ?>