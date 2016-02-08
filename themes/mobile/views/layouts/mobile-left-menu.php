<?php
	
	// start empty
	$html = '';
	
	
	// the home link image
	//$html .= CHtml::tag('h2', array('style'=>'margin: 4%;'), 'Βωλάξ');
	$html .= CHtml::tag('div', array('style'=>'margin: 4%'), 
		CHtml::link(CHtml::image(Yii::app()->baseUrl . '/assets/images/logo2.png', 'Volax.gr', array('style'=>'max-width:100%;')), Yii::app()->homeUrl)
	);
	$html .= CHtml::tag('div', array('style'=>'margin: 4%; color: #aaa;'), 'Ενα μικρό γραφικό χωριό της Τήνου');
	// Βωλάξ, Τήνος. Τόπος όμορφος και ζωντανός. Χωριό αγαπημένο. Μέρος που θέλουμε να προστατέψουμε και να αναδείξουμε πιο πολύ από ποτέ! Εκτιμούμε όλα όσα μας προσφέρει μέσα απ\' την ιστορία και την κουλτούρα του,  μέσα από την αξεπέραστη φύση και τις αξίες των ανθρώπων του...<br />Ακολουθήστε μας!

	
	// then, the blog link
	//$blog_menu = '';
	//$blog_category = Category::model()->findByPk(124);
	//$blog_menu .= CHtml::tag('div', array('class'=>'cyan-menu'),
	//	$this->widget('zii.widgets.CMenu', array(
	//		'items'=>array(array('label'=>$blog_category->title, 'url'=>array('/category/view', 'id'=>$blog_category->id))),
	//		'htmlOptions'=>array('class'=>'compact-buttons-list'),
	//	), true)
	//);
	//$html .= $blog_menu;
	
	
	// then the "selides" menu
	$html .= CHtml::tag('h3', array('style'=>'margin: 1.5em 4% 4% 4%;'), 'Οι σελίδες');
	$html .= CHtml::tag('div', array('class'=>'gray-menu'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ', 'url'=>array('/category/view', 'id'=>103, 'title'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ')),
				array('label'=>'ΤΟΥ ΧΩΡΙΟΥ', 'url'=>array('/category/view', 'id'=>19, 'title'=>'ΤΟΥ ΧΩΡΙΟΥ')),
				array('label'=>'ΤΟΥ ΣΥΛΛΟΓΟΥ', 'url'=>array('/category/view', 'id'=>18, 'title'=>'ΤΟΥ ΣΥΛΛΟΓΟΥ')),
			),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	
	// the "DO" menu
	$html .= CHtml::tag('div', array('class'=>'black-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'ΔΕΙΤΕ', 'url'=>array('/post/list', 'tag'=>'VIDEO')),
				array('label'=>'ΑΚΟΥΣΤΕ', 'url'=>array('/post/list', 'tag'=>'AUDIO')),
				array('label'=>'ΚΑΤΕΒΑΣΤΕ', 'url'=>array('/post/list', 'tag'=>'ΚΑΤΕΒΑΣΤΕ')),
			),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	
	
	// then, columns menu
	$html .= CHtml::tag('div', array('class'=>'cyan-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>Category::getCMenuItems(3),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	
	
	// for narrow screens we need more options: login/logout and the footer links
	$items = array();
	if (Yii::app()->user->isGuest)
	{
		$items[] = array('label'=>'Είσοδος', 'url'=>array('/user/login'));
		$items[] = array('label'=>'Εγγραφή', 'url'=>array('/user/register'));
	}
	else
	{
		if (Yii::app()->user->isAuthor || Yii::app()->user->isAdmin)
			$items[] = array('label'=>'Διαχείριση', 'url'=>array('/admin'));
		
		$items[] = array('label'=>'Ο λογαριασμός μου', 'url'=>array('/user/myAccount'));
		$items[] = array('label'=>'Εξοδος', 'url'=>array('/user/logout'));
	}
	$items[] = array('label'=>'Όροι χρήσης', 'url'=>array('/page/view', 'url_keyword'=>'terms'));
	
	$user_menu = CHtml::tag('div', array('class'=>'gray-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$items,
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	// on dropdown_menu only
	$html .= $user_menu;
	

	echo $html;

