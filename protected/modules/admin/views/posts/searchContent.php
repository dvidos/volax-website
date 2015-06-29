<h1>Αναζήτηση περιεχομένου</h1>
<?php
	$c1 = CHtml::tag('label', array(), 'Λήμμα');
	$c2 = 
		CHtml::textField('key', $key, array('size'=>40)) . '<br />' .
		CHtml::checkBox('regex', $regex) . ' Αναζήτηση ως regular expression' . '<br />' .
		CHtml::submitButton('Αναζήτηση');
	
	$row = CHtml::tag('tr', array(), CHtml::tag('td', array(), $c1) . CHtml::tag('td', array(), $c2));
	
	echo CHtml::beginForm(null, 'get');
	echo CHtml::tag('table', array('style'=>'width: auto;'), $row);
	echo CHtml::endForm('');
	
	echo '<hr />';
	
	echo CHtml::tag('p', array(), count($results) . ' αποτελέσματα');
	
	if (!empty($results))
	{
		echo '<table class="bordered">';
		foreach ($results as $item)
		{
			$c1 = CHtml::link(CHtml::encode($item['title']), array('/admin/posts/update', 'id'=>$item['id']));
			$c2 = '';
			echo CHtml::tag('tr', array(), CHtml::tag('td', array(), $c1) .  CHtml::tag('td', array(), $c2));
		}
		echo '</table>';
	}
?>


