<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/author.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?> - Συντάκτης</title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">Συντάκτης <?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<div style="float:left;">
			<?php 
				$this->widget('zii.widgets.CMenu',array(
					'activeCssClass'=>'active',
					'activateParents'=>true,
					'items'=>array(
						array('label'=>'Αρχική', 'url'=>array('/author')),
						array('label'=>'Αναρτήσεις', 'url'=>array('/author/posts')),
						array('label'=>'Αρχεία', 'url'=>array('/author/files')),
					),
				)); ?>
		</div><div style="float:right;">
			<?php 
				$this->widget('zii.widgets.CMenu',array(
					'activeCssClass'=>'active',
					'activateParents'=>true,
					'items'=>array(
						array('label'=>'Website', 'url'=>array('/')),
						array('label'=>'Εξοδος ' . Yii::app()->user->name, 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
					),
				)); ?>
		</div>
		<div style="clear:both;"></div>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		<p>Volax.gr version <?php echo Yii::app()->params['version']; ?>, 
		<?php echo Yii::app()->params['copyrightInfo']; ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>