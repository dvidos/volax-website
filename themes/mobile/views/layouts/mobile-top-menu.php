<?php
	// prepare the menus. 
	// must be two different ones, for when on the narrow screen, we hide the menu using jquery, 
	// and then when rotating back to landscape the menu is hidden!
	
	
	// start empty
	$html = '';
	
	
	// the home link image
	$html .= CHtml::tag('div', array('style'=>'padding: .5em 1em;'), 
		CHtml::link(
			CHtml::image(Yii::app()->baseUrl . '/assets/images/logo2.png', 'Volax.gr', array('style'=>'max-width:4em; vertical-align: middle;')),
			array('/')
		) . ' ' .
		CHtml::link(
			CHtml::tag('h2', array('style'=>'display:inline; margin: .5em; vertical-align: middle;'), 'Βωλάξ'),
			array('/'),
			array('style'=>'text-decoration:none; color: #555;')
		) . '<br>' . 
		CHtml::tag('p', array('style'=>'color: #aaa; margin: 0;'), 'Ενα μικρό γραφικό χωριό της Τήνου')
	);
	
	
	$html .= CHtml::tag('div', array('class'=>'gray-menu', 'style'=>'margin-bottom: 1em;'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(
				array('label'=>'Αρχική', 'url'=>array('/')),
			),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	
	
	
	// then, the blog link
	$blog_menu = '';
	$blog_category = Category::model()->findByPk(Yii::app()->params['leftColumnBlogCategoryId']);
	$blog_menu .= CHtml::tag('div', array('class'=>'cyan-menu'),
		$this->widget('zii.widgets.CMenu', array(
			'items'=>array(array('label'=>$blog_category->title, 'url'=>array('/category/view', 'id'=>$blog_category->id))),
			'htmlOptions'=>array('class'=>'compact-buttons-list'),
		), true)
	);
	$blog_menu .= CHtml::tag('p', array('style'=>'margin: 4%;'), $blog_category->prologue);
	
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
	
	
	

	
	


	
	// for narrow screens we need more options: login/logout and the footer links
	$items = array();
	if (Yii::app()->user->isGuest)
		$items[] = array('label'=>'Είσοδος', 'url'=>array('/site/login'));
	else
	{
		if (Yii::app()->user->isAuthor)
			$items[] = array('label'=>'Σύνταξη', 'url'=>array('/author'));
		
		if (Yii::app()->user->isAdmin)
			$items[] = array('label'=>'Διαχείριση', 'url'=>array('/admin'));
		
		$items[] = array('label'=>'Εξοδος', 'url'=>array('/site/logout'));
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
	

