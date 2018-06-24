<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/admin.css" />
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/images/favicon/logo2.png" />
	<title><?php echo CHtml::encode($this->pageTitle); ?> - Διαχείριση</title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo-right">
			<?php echo CHtml::link("Επιστροφή", Yii::app()->homeUrl); ?>
			&nbsp;·&nbsp;
			<?php echo CHtml::link("Αποσύνδεση", array('/user/logout')); ?>
		</div>
		<div id="logo">Διαχείριση <?php echo CHtml::encode(Yii::app()->name); ?></div>
		<div id="sublogo"><?php echo Yii::app()->user->user->getGreeting(); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<div style="float:left;">
			<?php 
				$more_items = array(
					array('label'=>'Κατηγορίες αναρτήσεων', 'url'=>array('/admin/categories'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Σχόλια αναρτήσεων', 'url'=>array('/admin/comments'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Ιστορικό αναρτήσεων', 'url'=>array('/admin/postRevisions'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Διαφημίσεις', 'url'=>array('/admin/advertisements'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Καταστάσεις δημοσίευσης', 'url'=>array('/admin/statuses'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Σελίδες κειμένου', 'url'=>array('/admin/pages'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Χρήστες', 'url'=>array('/admin/users'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Διάφορα εργαλεία', 'url'=>array('/admin/tools'), 'visible'=>Yii::app()->user->isAdmin),
				);
				$geo_items = array(
					array('label'=>'Εγγραφές', 'url'=>array('/admin/geoFeatures'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Waypoints', 'url'=>array('/admin/geoWaypoints'), 'visible'=>Yii::app()->user->isAdmin),
					array('label'=>'Ομάδες', 'url'=>array('/admin/geoGroups'), 'visible'=>Yii::app()->user->isAdmin),
				);
				$this->widget('zii.widgets.CMenu',array(
					'activeCssClass'=>'active',
					'activateParents'=>true,
					'items'=>array(
						array('label'=>'Επισκόπηση', 'url'=>array('/admin')),
						array('label'=>'Αναρτήσεις', 'url'=>array('/admin/posts')),
						array('label'=>'Αρχεία', 'url'=>array('/admin/files')),
						array('label'=>'Tags', 'url'=>array('/admin/tags'), 'visible'=>Yii::app()->user->isAdmin),
						array('label'=>'Γεωδαιτικά', 'url'=>'#', 'items'=>$geo_items, 'visible'=>Yii::app()->user->isAdmin),
						array('label'=>'Περισσότερα...', 'url'=>'#', 'items'=>$more_items, 'visible'=>Yii::app()->user->isAdmin),
					),
				)); ?>
		</div><div style="float:right;">
			<!-- here was the link to public website -->
		</div>
		<div style="clear:both;"></div>
	</div><!-- mainmenu -->

		
	<div class="container">
		<div id="content">
			<?php 
				if(Yii::app()->user->hasFlash('success'))
				{
					echo CHtml::tag('p', array('class'=>'flash-success'), CHtml::encode(Yii::app()->user->getFlash('success')));
					$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
					echo CHtml::tag('script', array(), $js);
				}
				if(Yii::app()->user->hasFlash('error'))
				{
					echo CHtml::tag('p', array('class'=>'flash-error'), CHtml::encode(Yii::app()->user->getFlash('error')));
					$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-error").slideUp(); }, 4000); });';
					echo CHtml::tag('script', array(), $js);
				}
			?>
			
			<?php echo $content; ?>
		</div><!-- content -->
	</div>

	
	<div id="footer">
		<p><?php echo Yii::app()->params['copyrightInfo']; ?></p>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>