<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php 
		if ($this->pageTitle != '')
			echo CHtml::encode($this->pageTitle) . ' - ';
		echo Yii::app()->name;
	?></title>
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/images/favicon/logo2.png" />
	
	<?php /* the following needed for mobile devices, tells them we shall manipulate our width and keep same scale when rotating */ ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/mobile.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/print.css" media="print" />
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	
	<script>
		// toggling of compact menu buttons
		$(document).ready(function () {
			$('ul.compact-buttons-list > li > a').click(function(){
				if ($(this).attr('class') != 'active'){
					$('ul.compact-buttons-list li ul').slideUp();
					$(this).next().slideToggle();
					$('ul.compact-buttons-list li a').removeClass('active');
					$(this).addClass('active');
				}else{
					$('ul.compact-buttons-list li ul').slideUp();
					$('ul.compact-buttons-list li a').removeClass('active');
				}
				// if <li> has no <ul>, it should be allowed to be clicked.
				var clickable = ($(this).parent().children('ul').size() == 0);
				return clickable;
			});
		});
	</script>
	
	
	<!--
		divs for responsive design:
		- top	(top black zone)
		- main-menu	(logo with home link and other menu items)
		- banner	(for advertisements)
		- content	(content)
		- footer	(footer)
	-->


</head>
<body>
	
	<div id="top">
		<div class="narrow-screens">
			<a href="#" 
				onClick="$('#main-menu-dropdown').slideToggle('slow'); return false;" 
				style="display:block;"><?php 
					echo CHtml::image(Yii::app()->baseUrl . '/assets/images/menu-icon-white.png', '', array('style'=>'width:1em;')); 
				?> &nbsp; Βωλάξ
			</a>
		</div>
		<div class="wide-screens">
			<div style="float:left;">
				<?php 
					echo CHtml::link('Βωλάξ', Yii::app()->homeUrl) . ' &nbsp;|&nbsp; ';
					echo CHtml::link('Επισκεφτείτε μας', array('/category/view', 'id'=>127)) . ' &nbsp;|&nbsp; ';
					echo CHtml::link('Ποιοί είμαστε', array('/page/view', 'url_keyword'=>'whoweare')) . ' &nbsp;|&nbsp; ';
					echo CHtml::link('Επικοινωνία', array('/site/contact'));
				?>
			</div>
			<div style="float:right;">
				<?php
					/*
					if (Yii::app()->user->isGuest)
						echo CHtml::link('Είσοδος', array('/site/login'));
					else
					{
						if (Yii::app()->user->isAuthor)
							echo CHtml::link('Σύνταξη', array('/author')) . ' &nbsp;|&nbsp; ';
						
						if (Yii::app()->user->isAdmin)
							echo CHtml::link('Διαχ', array('/admin')) . ' &nbsp;|&nbsp; ';
						
						echo CHtml::link('Έξοδος ' . CHtml::encode(Yii::app()->user->user->username), array('/site/logout'));
					}
					*/
				?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	

	<!--
		there must be two different menus, for when on the narrow screen, we hide the menu using jquery, 
		and then when rotating back to landscape the menu is hidden!
		narrow screens get a full-width drop-down menu
		wide screens get a permanent menu floated left
	-->
	
	<div id="main-menu-dropdown">
		<?php $this->renderPartial('//layouts/mobile-top-menu'); ?>
	</div>
	
	<div id="main-menu-left">
		<?php $this->renderPartial('//layouts/mobile-left-menu'); ?>
	</div>
	
	
	
	<!-- banners location -->
	<div id="banner">
		<?php
			$this->widget('application.components.AdvertisementWidget', array(
				'htmlOptions'=>array(
					//'style'=>'background-color: #777; border: 1px solid red; width: 700x; height: 140px; overflow: hidden;'
					'style'=>''
				),
			));
		?>
	</div>
	
	
	<div id="content">
		<?php echo $content; ?>
	</div>
	
	
	
	
	
	<div id="footer">
		<div class="narrow-screens">
			<?php /* don't encode */ echo Yii::app()->params['copyrightInfo']; ?>
		</div>
		<div class="wide-screens">
			<div style="float:left;">
				Copyright &copy; 2008 - <?php echo date('Y'); ?> volax.gr
			</div>
			<div style="float:right;">
				<?php echo CHtml::link('Οροι χρήσης', array('/page/view', 'url_keyword'=>'terms')); ?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	
	

	<!-- Google Analytics Code -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-4488638-1', 'volax.gr');
		ga('send', 'pageview');
	</script>

</body>
</html>

