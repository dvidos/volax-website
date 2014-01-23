<div style="float:right; padding-top: .5em;">
	<?php echo CHtml::link('Οδηγίες', array('/page/view', 'url_keyword'=>'editorNotes')); ?>
</div>
<h1>Νέα ανάρτηση</h1>
<div style="clear:both;"></div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>