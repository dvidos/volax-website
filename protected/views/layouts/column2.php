<?php $this->beginContent('/layouts/main'); ?>

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->


<div class="span-18">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<div class="span-6 last">
	<div id="sidebar">
		<?php if(!Yii::app()->user->isGuest) $this->widget('UserMenu'); ?>

		<?php $this->widget('TagCloud', array(
			'maxTags'=>Yii::app()->params['tagCloudCount'],
		)); ?>

		<?php $this->widget('RecentComments', array(
			'maxComments'=>Yii::app()->params['recentCommentCount'],
		)); ?>
	</div><!-- sidebar -->
</div>

<?php $this->endContent(); ?>