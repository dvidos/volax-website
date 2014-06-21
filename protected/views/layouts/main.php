<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php 
		if ($this->pageTitle != '')
			echo CHtml::encode($this->pageTitle) . ' - ';
		echo Yii::app()->name;
	?></title>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/stylistic.css"/>
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/images/favicon/logo.png" />
	<script src="assets/js/script.js"></script>
</head>
<body>
<div id="page" style="width:1200px;margin:0 auto;">



<div id="top-menu">
	<div id="top-menu-left" style="float:left;">
		<?php echo CHtml::link('Αρχική', array('/')); ?> 
		&nbsp;|&nbsp;
		<?php echo CHtml::link('Ποιοί είμαστε', array('/page/view', 'url_keyword'=>'whoweare')); ?> 
		&nbsp;|&nbsp;
		<?php echo CHtml::link('Επικοινωνία', array('/site/contact')); ?>
	</div>
	<div id="top-menu-right" style="float:right;">
		<?php
			if (Yii::app()->user->isGuest)
			{
				// echo CHtml::link('Γίνε Μέλος', array('/site/register'));
				// echo ' | ';
				echo CHtml::link('Είσοδος', array('/site/login'));
			}
			else
			{
				$h = date('G');
				$greeting = '';
				if ($h < 7) $greeting = 'Είναι αργά';
				else if ($h < 12) $greeting = 'Καλημέρα';
				else if ($h < 16) $greeting = 'Καλό μεσημέρι';
				else if ($h < 20) $greeting = 'Καλό απόγευμα';
				else $greeting = 'Καλησπέρα';
				
				echo CHtml::tag('b', array(), $greeting . ' ' . CHtml::encode(Yii::app()->user->user->username)) . ' &nbsp;|&nbsp; ';
				
				if (Yii::app()->user->isAuthor)
					echo CHtml::link('Σύνταξη', array('/author')) . ' &nbsp;|&nbsp; ';
				
				if (Yii::app()->user->isAdmin)
					echo CHtml::link('Διαχείριση', array('/admin')) . ' &nbsp;|&nbsp; ';
				
				echo CHtml::link('Έξοδος', array('/site/logout'));
			}
		?>
	</div>
	<div style="clear:both;"></div>
</div><!-- /top-menu -->



<div id="banner">
	<div id="banner-left" style="float:left; width:25%;">
		<?php
			$img = CHtml::image(Yii::app()->baseUrl . '/assets/images/logo.jpg');
			echo CHtml::link($img, array('/'))
		?>
	</div>
	<div id="banner-right" style="float:right; width:75%;">
		<?php
			$this->widget('application.components.AdvertisementWidget', array(
				'htmlOptions'=>array(
					//'style'=>'background-color: #777; border: 1px solid red; width: 700x; height: 140px; overflow: hidden;'
					'style'=>''
				),
			));
		?>
	</div>
	<div style="clear:both;"></div>
</div><!-- /banner -->



<div id="content">

	<div id="content-col1" style="float:left;width:220px;margin-right:80px;">
		<?php include('left_column.php'); ?>
	</div>
	<div id="content-col2" style="float:left;width:900px;">
		<?php echo $content; ?>
	</div>
	<div id="content-col4" style="float:left;width:0;">
	</div>
	<div style="clear:both;"></div>
	
</div><!-- /content -->




	
<div id="footer">
	<div id="footer-categories-list" style="display:none;">
		<div id="footer-col1" style="float:left;width:220px;margin-right:80px;">
			&nbsp;
		</div>
		<div id="footer-col2" style="float:left;width:900px;">
			<?php
				$c1 = '<b>Μόνιμες Στήλες</b>';
				$c1 .= $this->widget('zii.widgets.CMenu', array(
					'items'=>Category::getCMenuItems(3),
					'htmlOptions'=>array('class'=>'footerMenu'),
				), true);
				$c2 = '<b>Κάντε</b>';
				$c2 .= $this->widget('zii.widgets.CMenu', array(
					'items'=>Category::getCMenuItems(12),
					'htmlOptions'=>array('class'=>'footerMenu'),
				), true);
				$c3 = '<b>Οι σελίδες</b>';
				$c3 .= $this->widget('zii.widgets.CMenu', array(
					'items'=>Category::getCMenuItems(17),
					'htmlOptions'=>array('class'=>'footerMenu'),
				), true);
				
				$html = '<table width="100%"><tr>';
				$html .= '<td width="33%">' . $c1 . '</td>';
				$html .= '<td width="33%">' . $c2 . '</td>';
				$html .= '<td width="33%">' . $c3 . '</td>';
				$html .= '</tr></table>';
				echo $html;
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div id="footer-normal">
		<div id="footer-col1" style="float:left;width:270px;margin-right:30px;">
			&nbsp; Copyright &copy; 2013 - 2014, <b>D.Vidos &amp; L.Dustal</b>
		</div>
		<div id="footer-col2" style="float:left;width:900px;">
			<?php echo CHtml::link('Στήλες', '#', array('onClick'=>"$('#footer-categories-list').slideToggle(); return false;")); ?> 
			&nbsp;|&nbsp;
			<?php echo CHtml::link('Tags', array('/post/tags')); ?> 
			&nbsp;|&nbsp;
			<?php echo CHtml::link('Επικοινωνία', array('/site/contact')); ?> 
			&nbsp;|&nbsp;
			<?php echo CHtml::link('Οροι χρήσης', array('/page/view', 'url_keyword'=>'terms')); ?> 
		</div>
		<div style="clear:both;"></div>
	</div>
</div><!-- /footer -->


<!-- Google Analytics Code -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-4488638-1', 'volax.gr');
  ga('send', 'pageview');
</script>


</div><!-- /page -->
</body>
</html>

