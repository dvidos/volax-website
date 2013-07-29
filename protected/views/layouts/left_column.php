
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
			$this->widget('zii.widgets.CMenu', array(
				'items'=>Category::getCMenuItems(3),
				'htmlOptions'=>array('class'=>'multilevelMenu'),
			));
		?>
	</div>
	<div id="homepage-messages-area">
	</div>
	<div id="homepage-action-categories-list">
		<?php
			$cat = Category::model()->findByPk(12);
			foreach ($cat->subcategories as $category)
			{
				$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'category-link'));
				echo $link;
				echo '<span class="category-prologue">' . $category->prologue . '</span>';
			}
		?>
	</div>
	<div id="homepage-pages-categories-list">
		<?php
			echo '<h3>οι σελίδες</h3>';
			$this->widget('zii.widgets.CMenu', array(
				'items'=>Category::getCMenuItems(17),
				'htmlOptions'=>array('class'=>'multilevelMenu'),
			));
		?>
	</div>
