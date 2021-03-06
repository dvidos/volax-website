<?php
	// prepare the menus. 
	// must be two different ones, for when on the narrow screen, we hide the menu using jquery, 
	// and then when rotating back to landscape the menu is hidden!
	
	
	// start empty
	$html = '';
	
	
			
	
	// logo, title, moto.
	$html .= CHtml::tag('div', array('style'=>'padding: .5em .75em;'), 
		CHtml::link(
			CHtml::image(Yii::app()->baseUrl . '/assets/images/logo2.png', 'Volax.gr', array('style'=>'max-width:4em; vertical-align: middle;')),
			Yii::app()->homeUrl
		) . ' ' .
		CHtml::link(
			CHtml::tag('h2', array('style'=>'display:inline; margin: .5em; vertical-align: middle;'), 'Βωλάξ'),
			Yii::app()->homeUrl,
			array('style'=>'text-decoration:none; color: #555;')
		) . '<br>' . 
		CHtml::tag('p', array('style'=>'color: #aaa; margin: 0;'), 'Ενα μικρό γραφικό χωριό της Τήνου')
	);
	
	
	
	// home link
	$html .= CHtml::tag('div', array('class'=>'cyan-menu', 'style'=>'margin-bottom: 1em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'Αρχική', 'url'=>Yii::app()->homeUrl),
				array('label'=>'Επισκεφτείτε μας', 'url'=>array('/category/view', 'id'=>127, 'title'=>'Επισκευτείτε μας')),
			),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	
	
	// then, the blog link
	//$blog_menu = '';
	//$blog_category = Category::model()->findByPk(124);
	//$blog_menu .= CHtml::tag('div', array('class'=>'cyan-menu'),
	//	$this->widget('zii.widgets.CMenu', array(
	//		'items'=>array(array('label'=>$blog_category->title, 'url'=>array('/category/view', 'id'=>$blog_category->id))),
	//		'htmlOptions'=>array('class'=>'compact-buttons-list'),
	//	), true)
	//);
	// $blog_menu .= CHtml::tag('p', array('style'=>'margin: 4%;'), $blog_category->prologue);
	//$html .= $blog_menu;
	
	
	// then the "selides" menu
	
	$html .= CHtml::tag('h3', array('style'=>'margin: 1.5em 4% 4% 4%;'), 'Οι σελίδες');
	$html .= CHtml::tag('div', array('class'=>'gray-menu'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				//array('label'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ', 'url'=>array('/category/view', 'id'=>103, 'title'=>'ΤΗΣ ΚΑΤΑΣΚΗΝΩΣΗΣ')),
				array('label'=>'HELLO!', 'url'=>array('/category/view', 'id'=>124, 'title'=>'HELLO!')),
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
	$user_menu = CHtml::tag('div', array('class'=>'gray-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$items,
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	// on dropdown_menu only
	$html .= $user_menu;
	
	$items = array();
	$items[] = array('label'=>'Αναζήτηση', 'url'=>array('/site/search'));
	$items[] = array('label'=>'Ποιοί είμαστε', 'url'=>array('/page/view', 'url_keyword'=>'whoweare'));
	$items[] = array('label'=>'Επικοινωνία', 'url'=>array('/site/contact'));
	$items[] = array('label'=>'Όροι χρήσης', 'url'=>array('/page/view', 'url_keyword'=>'terms'));
	$footer_menu = CHtml::tag('div', array('class'=>'cyan-menu', 'style'=>'margin-top: 1.5em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$items,
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	// on dropdown_menu only
	$html .= $footer_menu;
	
	
	echo $html;
	

