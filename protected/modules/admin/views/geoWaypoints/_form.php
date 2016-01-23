<div class="form">
	<script src="<?php echo Yii::app()->baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table>
<tr><td width="66%" style="vertical-align: top;">

	<div class="row">
		<?php echo $form->labelEx($model,'feature_id'); ?>
		<?php echo $form->dropDownList($model,'feature_id',  CHtml::listData(GeoFeature::model()->findAll(array('order'=>'title', 'condition'=>"(feature_type='route' OR feature_type='area')")), 'id', 'title')); ?>
		<?php echo $form->error($model,'feature_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'waypoint_no'); ?>
		<?php echo $form->textField($model,'waypoint_no',array('size'=>'10')); ?>
		<?php echo $form->error($model,'waypoint_no'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>'40', 'maxlength'=>200)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>'40', 'maxlength'=>200)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'geo_lat'); ?>
		<?php echo $form->textField($model,'geo_lat',array('size'=>'15')); ?>
		<?php echo $form->error($model,'geo_lat'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'geo_long'); ?>
		<?php echo $form->textField($model,'geo_long',array('size'=>'15')); ?>
		<?php echo $form->error($model,'geo_long'); ?>
	</div>

	<div class="row">
		(<?php echo CHtml::link('γραφική αναζήτηση με χάρτη', 'http://itouchmap.com/latlong.html', array('target'=>'_blank', 'style'=>'font-weight:normal;color:#555;')); ?>)
	</div>
	
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση'); ?>
	
	
</td><td width="33%" style="vertical-align: top;">

	&nbsp;

</td></tr>
</table>

<?php $this->endWidget(); ?>

</div><!-- form -->