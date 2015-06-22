<h1>Εικόνες εντός αναρτήσεων</h1>
<?php
	$rootPath = dirname(Yii::app()->basePath); // basePath points to protected
	echo CHtml::tag('p', array(), 'Root path is ' . $rootPath);

	echo '<table class="bordered">';
	foreach ($data as $item)
	{
		$c1 = CHtml::link(CHtml::encode($item['title']), array('/admin/posts/update', 'id'=>$item['id']));
		$c2 = '';
		
		foreach ($item['images'] as $image)
		{
			// this just for test. will save in content later...
			if (substr($image, 0, 19) == 'http://www.volax.gr')
				$image = substr($image, 19);
			if (substr($image, 0, 15) == 'http://volax.gr')
				$image = substr($image, 15);
			//if (substr($image, 0, 1) != '/')
			//	$image = '/' . $image;
			
			// some data:image/jpeg are really big, for someone is dragging and dropping them...
			if (substr($image, 0, 11) == 'data:image/')
			{
				$image = substr($image, 0, 20) . '...';
				$color = '#c00';
			}
			else
			{
				$image = urldecode($image);
				$exists = file_exists($rootPath . $image);
				$color = $exists ? '#0c0' : '#c00';
			}				
				
			
			
			$c2 .= '<div style="color:'.$color.';">' . $image . '</div>';
		}
		
		echo CHtml::tag('tr', array(), CHtml::tag('td', array(), $c1) .  CHtml::tag('td', array(), $c2));
	}
	echo '</table>';
?>


