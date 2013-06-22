<?php $this->beginContent('/layouts/main'); ?>

	<div id="content-col1" style="float:left;width:18%;margin-right:4%;">
		
		<div id="homepage-explanation">
			Βωλάξ, Τήνος. 
			Τόπος όμορφος και ζωντανός.
			Χωριό αγαπημένο. 
			Μέρος που θέλουμε να προστατέψουμε και να αναδείξουμε &mdash;πιο πολύ από ποτέ!
			Εκτιμούμε όλα όσα μας προσφέρει μέσα απ' την ιστορία και την κουλτούρα του, 
			μέσα από την αξεπέραστη φύση του και τις αξίες των ανθρώπων του...<br />
			Ακολουθήστε μας!
		</div>
		<div id="homepage-main-categories-list">
			<?php   
				$cat = Category::model()->findByPk(3);
				foreach ($cat->subcategories as $category)
				{
					$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
					echo $link;
				}
			?>
		</div>
	</div>
	<div id="content-col2" style="float:left;width:56%;">
		<?php echo $content; ?>
	</div>
	<div id="content-col4" style="float:left;width:18%;margin-left:4%;">
		<div id="homepage-messages-area">
			<?php
				// $this->widget('application.components.SlideshowWidget', array(
					// 'directory'=>'uploads/slideshows/homepage',
					// 'htmlOptions'=>array(
						// 'style'=>'border: 1px solid red;',
					// ),
				// ));
			?>
		</div>
		<div id="homepage-action-categories-list">
			<?php
				$cat = Category::model()->findByPk(12);
				foreach ($cat->subcategories as $category)
				{
					$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
					echo $link;
					echo '<span class="category-subtitle">' . $category->subtitle . '</span>';
				}
			?>
		</div>
		<div id="homepage-pages-categories-list">
			<?php
				echo '<h3>οι σελίδες</h3>';
				$cat = Category::model()->findByPk(17);
				foreach ($cat->subcategories as $category)
				{
					$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
					echo $link;
				}
			?>
		</div>
	</div>
	<div style="clear:both;"></div>

<?php $this->endContent(); ?>