<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/admin.css" />
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/images/favicon/logo2.png" />
	<title><?php echo CHtml::encode($this->pageTitle); ?> - Διαχείριση</title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">Διαχείριση <?php echo CHtml::encode(Yii::app()->name); ?> - <?php echo CHtml::encode(Yii::app()->user->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<div style="float:left;">
			<?php 
				$submenu_items = array(
					array('label'=>'Κατηγορίες', 'url'=>array('/admin/categories')),
					array('label'=>'Tags', 'url'=>array('/admin/tags')),
					array('label'=>'Διαφημίσεις', 'url'=>array('/admin/advertisements')),
					array('label'=>'Snippets', 'url'=>array('/admin/snippets')),
					array('label'=>'Καταστάσεις', 'url'=>array('/admin/statuses')),
					array('label'=>'Σελίδες', 'url'=>array('/admin/pages')),
					array('label'=>'Χρήστες', 'url'=>array('/admin/users')),
					array('label'=>'Σημειώσεις', 'url'=>array('/admin/dashboard/page', 'page'=>'notes')),
				);
				$this->widget('zii.widgets.CMenu',array(
					'activeCssClass'=>'active',
					'activateParents'=>true,
					'items'=>array(
						array('label'=>'Αρχική', 'url'=>array('/admin')),
						array('label'=>'Αναρτήσεις', 'url'=>array('/admin/posts')),
						array('label'=>'Αρχεία', 'url'=>array('/admin/files')),
						array('label'=>'Σχόλια', 'url'=>array('/admin/comments')),
						array('label'=>'...', 'url'=>'#', 'items'=>$submenu_items),
					),
				)); ?>
		</div><div style="float:right;">
			<?php 
				$this->widget('zii.widgets.CMenu',array(
					'activeCssClass'=>'active',
					'activateParents'=>true,
					'items'=>array(
						array('label'=>'Επιστροφή', 'url'=>array('/')),
						//array('label'=>'Εξοδος ' . Yii::app()->user->name, 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
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