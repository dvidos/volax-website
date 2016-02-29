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
			// $category = Category::model()->findByPk(124);
			// $link = CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('class'=>'xcategory-link'));
			// echo $link;
		?>
	</div> -->
	<div class="section">
		<?php
			echo '<h3>οι σελίδες</h3>';
			$this->widget('zii.widgets.CMenu', array(
				'items'=>array(
					// array('label'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ', 'url'=>array('/category/view', 'id'=>103, 'title'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ')),
					array('label'=>'HELLO!', 'url'=>array('/category/view', 'id'=>124, 'title'=>'HELLO!')),
					array('label'=>'ΤΟΥ ΧΩΡΙΟΥ', 'url'=>array('/category/view', 'id'=>19, 'title'=>'ΤΟΥ ΧΩΡΙΟΥ')),
					array('label'=>'ΤΟΥ ΣΥΛΛΟΓΟΥ', 'url'=>array('/category/view', 'id'=>18, 'title'=>'ΤΟΥ ΣΥΛΛΟΓΟΥ')),
				),
				'htmlOptions'=>array('class'=>'main-menu-list'),
			));
		?>
	</div>
	<div class="section">
		<?php
			echo CHtml::link('ΔΕΙΤΕ <span class="subtitle">video</span>', array('/post/list', 'tag'=>'VIDEO'), array('class'=>'black-button'));
			echo CHtml::link('ΑΚΟΥΣΤΕ <span class="subtitle">ηχητικά</span>', array('/post/list', 'tag'=>'AUDIO'), array('class'=>'black-button'));
			echo CHtml::link('ΚΑΤΕΒΑΣΤΕ <span class="subtitle">αρχεία</span>', array('/post/list', 'tag'=>'ΚΑΤΕΒΑΣΤΕ'), array('class'=>'black-button'));
		?>
	</div>
	<div class="section">
		<?php
			$this->widget('zii.widgets.CMenu', array(
				'items'=>Category::getCMenuItems(3),
				'htmlOptions'=>array('class'=>'main-menu-list not-multilevelMenu'),
			));
		?>
	</div>

	
	
</div>