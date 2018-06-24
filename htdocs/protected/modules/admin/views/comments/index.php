<h1>Σχόλια</h1>

<table style="width:auto;">
	<tr><td>
		<?php
			echo CHtml::link('Ολα', array(''), array('class'=>'button')); 
			echo ' ';
			echo CHtml::link('Εκκρεμή', array('', 'status'=>Comment::STATUS_PENDING), array('class'=>'button')); 
			echo ' ';
			echo CHtml::link('Eγκεκριμένα', array('', 'status'=>Comment::STATUS_APPROVED), array('class'=>'button')); 
		?>	
	</td><td style="vertical-align: middle;">
		<?php
			echo CHtml::beginForm(array(''), 'GET', array('style'=>''));
			echo ' Post # ';
			echo CHtml::textField('post_id', @$_REQUEST['post_id'], array('size'=>4));
			echo CHtml::endForm();
		?>	
	</td></tr>
</table>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
