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
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/images/favicon/logo2.png" />
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/script.js"></script>
	<?php 
		if (Yii::app()->openGraph->image == '')
			Yii::app()->openGraph->image = Yii::app()->request->baseUrl . '/assets/images/favicon/logo2.png';
		Yii::app()->openGraph->render(); 
	?>
	
</head>
<body class="map-layout">



<div id="top-menu">
	<div id="top-menu-left" style="float:left;">
		<?php echo CHtml::link('Αρχική', Yii::app()->homeUrl); ?> 
		&nbsp;&middot;&nbsp;
		<?php echo CHtml::link('Επισκεφτείτε μας', array('/category/view', 'id'=>127)); ?> 
		&nbsp;&middot;&nbsp;
		<?php echo CHtml::link('Αναζήτηση', array('/site/search')); ?> 
		&nbsp;&middot;&nbsp;
		<?php echo CHtml::link('Ποιοί είμαστε', array('/page/view', 'url_keyword'=>'whoweare')); ?> 
		&nbsp;&middot;&nbsp;
		<?php echo CHtml::link('Επικοινωνία', array('/site/contact')); ?>
	</div>
	<div id="top-menu-right" style="float:right;">
		<?php
			if (Yii::app()->user->isGuest)
			{
				echo CHtml::link('Είσοδος', array('/user/login'));
				echo ' &middot; ';
				echo CHtml::link('Εγγραφή', array('/user/register'));
			}
			else
			{
				echo CHtml::tag('b', array(), Yii::app()->user->user->getGreeting()) . ' &nbsp;&middot;&nbsp; ';
				
				if (Yii::app()->user->isAdmin || Yii::app()->user->isAuthor)
					echo CHtml::link('Διαχείριση', array('/admin')) . ' &nbsp;&middot;&nbsp; ';
				
				echo CHtml::link('O λογαριασμός μου', array('/user/myAccount'));
				echo ' &middot; ';
				echo CHtml::link('Έξοδος', array('/user/logout'));
			}
		?>
	</div>
	<div style="clear:both;"></div>
</div><!-- /top-menu -->


<div id="content">
	<?php echo $content; ?>
</div>

	
<div id="footer">
	<div id="footer-normal">
		<div id="footer-col1" style="float:left;width:270px;margin-right:30px;">
			&nbsp;&nbsp;<?php /* don't encode */ echo Yii::app()->params['copyrightInfo']; ?>
		</div>
		<div id="footer-col2" style="float:left;width:900px;">
			<?php echo CHtml::link('Tags', array('/post/tags')); ?> 
			&nbsp;&middot;&nbsp;
			<?php echo CHtml::link('Επικοινωνία', array('/site/contact')); ?> 
			&nbsp;&middot;&nbsp;
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


</body>
</html>

