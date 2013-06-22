<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/admin.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?> - Administration Area</title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?> administration</div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Admin', 'url'=>array('/admin')),
				array('label'=>'Website', 'url'=>array('/')),
				array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
				
			),
		)); ?>
	</div><!-- mainmenu -->
	<div id="submenu">
		<?php echo CHtml::link('Posts',array('/admin/posts')); ?> |
		<?php echo CHtml::link('Files',array('/admin/files')); ?> |
		<?php echo CHtml::link('Comments',array('/admin/comments')); ?> |
		<?php echo CHtml::link('Categories',array('/admin/categories')); ?> |
		<?php echo CHtml::link('Users',array('/admin/users')); ?> |
		<?php echo CHtml::link('Tags',array('/admin/tags')); ?> |
		<?php echo CHtml::link('Ads',array('/admin/ads')); ?> |
		<?php echo CHtml::link('Snippets',array('/admin/snippets')); ?> |
		<?php echo CHtml::link('Notes',array('/admin/dashboard/page', 'page'=>'notes')); ?>
	</div>

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by D.V. &amp; D.V. All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>