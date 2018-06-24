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
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('style'=>'width:100%;','maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description', array('style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'description'); ?>
		<?php $this->widget('application.components.CkEditorWidget', array('varName' => 'GeoFeature_description', 'height'=>200)); ?>
	</div>

	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση'); ?>
	
	
</td><td width="33%" style="vertical-align: top;">
	
	<div class="row">
		<?php echo $form->labelEx($model,'feature_type'); ?>
		<?php echo $form->dropDownList($model,'feature_type', GeoFeature::getFeatureTypeOptions()); ?>
		<?php echo $form->error($model,'feature_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->dropDownList($model,'group_id',  CHtml::listData(GeoGroup::model()->findAll(array('order'=>'view_order,title')), 'id', 'title')); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'geo_lat'); ?>
		<?php echo $form->textField($model,'geo_lat'); ?>
		<?php echo $form->error($model,'geo_lat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'geo_long'); ?>
		<?php echo $form->textField($model,'geo_long'); ?>
		<?php echo $form->error($model,'geo_long'); ?>
	</div>

	<div class="row">
		(<?php echo CHtml::link('γραφική αναζήτηση με χάρτη', 'http://itouchmap.com/latlong.html', array('target'=>'_blank', 'style'=>'font-weight:normal;color:#555;')); ?>)
	</div>
	
	<div class="row">
		<?php echo $form->checkBox($model,'active'); ?>
		<?php echo $form->labelEx($model,'active', array('style'=>'display:inline; margin-left:.5em;')); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

</td></tr>
</table>

<?php $this->endWidget(); ?>

</div><!-- form -->