<div id="left-column">


	<div id="moto">
		Βωλάξ, Τήνος. 
		Τόπος όμορφος και ζωντανός.
		Χωριό αγαπημένο. 
		Μέρος που θέλουμε να προστατέψουμε και να αναδείξουμε πιο πολύ από ποτέ!
		Εκτιμούμε όλα όσα μας προσφέρει μέσα απ' την ιστορία και την κουλτούρα του, 
		μέσα από την αξεπέραστη φύση και τις αξίες των ανθρώπων του...<br />
		Ακολουθήστε μας!
	</div>
	<!-- <div class="blue-buttons">
		<?php
			$blogId = Yii::app()->params['leftColumnBlogCategoryId'];
			$category = Category::model()->findByPk($blogId);
			$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'xcategory-link'));
			echo $link;
			echo '<div class="prologue">' . $category->prologue . '</div>';
		?>
	</div> -->
	<div class="blue-buttons">
		<?php
			echo '<h3>οι σελίδες</h3>';
			$this->widget('zii.widgets.CMenu', array(
				'items'=>array(
					array('label'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ', 'url'=>array('/category/view', 'id'=>103)),
					array('label'=>'ΤΟΥ ΧΩΡΙΟΥ', 'url'=>array('/category/view', 'id'=>19)),
					array('label'=>'ΤΟΥ ΣΥΛΛΟΓΟΥ', 'url'=>array('/category/view', 'id'=>18)),
				),
				'htmlOptions'=>array('class'=>'multilevelMenu'),
			));
		?>
	</div>
	<div class="black-buttons">
		<?php
			$cat = Category::model()->findByPk(12);
			foreach ($cat->subcategories as $category)
			{
				$link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'xcategory-link'));
				echo $link;
				echo '<div class="prologue">' . $category->prologue . '</div>';
			}
		?>
	</div>
	<div class="blue-buttons">
		<?php
			$this->widget('zii.widgets.CMenu', array(
				'items'=>Category::getCMenuItems(3),
				'htmlOptions'=>array('class'=>'multilevelMenu'),
			));
		?>
	</div>
	<div class="xhomepage-messages-area">
	</div>

	
	
</div>