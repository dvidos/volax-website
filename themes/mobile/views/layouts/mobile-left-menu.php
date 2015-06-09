<?php
	
	// start empty
	$html = '';
	
	
	// the home link image
	//$html .= CHtml::tag('h2', array('style'=>'margin: 4%;'), 'Βωλάξ');
	$html .= CHtml::tag('div', array('style'=>'margin: 4%'), 
		CHtml::link(CHtml::image(Yii::app()->baseUrl . '/assets/images/logo2.png', 'Volax.gr', array('style'=>'max-width:100%;')), array('/'))
	);
	$html .= CHtml::tag('div', array('style'=>'margin: 4%; color: #aaa;'), 'Ενα μικρό γραφικό χωριό της Τήνου');
	// Βωλάξ, Τήνος. Τόπος όμορφος και ζωντανός. Χωριό αγαπημένο. Μέρος που θέλουμε να προστατέψουμε και να αναδείξουμε πιο πολύ από ποτέ! Εκτιμούμε όλα όσα μας προσφέρει μέσα απ\' την ιστορία και την κουλτούρα του,  μέσα από την αξεπέραστη φύση και τις αξίες των ανθρώπων του...<br />Ακολουθήστε μας!

	
	// then, the blog link
	$blog_menu = '';
	$blog_category = Category::model()->findByPk(Yii::app()->params['leftColumnBlogCategoryId']);
	$blog_menu .= CHtml::tag('div', array('class'=>'cyan-menu'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(array('label'=>$blog_category->title, 'url'=>array('/category/view', 'id'=>$blog_category->id))),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	// on both menus
	$html .= $blog_menu;
	
	
	// then the "selides" menu
	$pages_menu = '';
	$pages_menu .= CHtml::tag('h3', array('style'=>'margin: 1.5em 4% 4% 4%;'), 'Οι σελίδες');
	$pages_menu .= CHtml::tag('div', array('class'=>'gray-menu'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>Category::getCMenuItems(17),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true));
	
	// on both menus
	$html .= $pages_menu;
	
	
	// then, columns menu
	$columns_menu = CHtml::tag('div', array('class'=>'cyan-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>Category::getCMenuItems(3),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	// on both menus
	$html .= $columns_menu;
	
	echo $html;
	
	
