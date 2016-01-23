<h1>Εισαγωγή αρχείου</h1>

<p>Τί ακριβώς θέλετε να εισάγετε από το <?php echo $imported['filename'] ?>;</p>
<table class="bordered">
	<?php
		function googleMapLink($caption, $lat, $lon)
		{
			$url = 'http://maps.google.com/maps';
			$url .= '?q=' . $lat . ',' . $lon; // marker
			$url .= '&t=k';  // type keyhole (satelite)
			$url .= '&z=20';  // zoom level, up to 23
			
			return CHtml::link($caption, $url, array('target'=>'_blank'));
		}
		
		
		if (count($imported['waypoints']) > 0)
		{
			echo '<tr><td>';
			echo 'Διαδρομή με όνομα <b>' . $imported['name'] . '</b> με ' . count($imported['waypoints']) . ' σημεία';
			
			$first = $imported['waypoints'][0];
			$last = $imported['waypoints'][count($imported['waypoints']) - 1];
			
			echo ' (' . googleMapLink('αρχή', $first['lat'], $first['lon']) . ', ';
			echo googleMapLink('τέλος', $last['lat'], $last['lon']) . ')';
			
			echo '</td><td>';

			$points = array();
			foreach ($imported['waypoints'] as $waypoint)
				$points[] = $waypoint['lat'] . ',' . $waypoint['lon'];
			
			echo CHtml::beginForm($this->createUrl('import'), 'post');
			echo CHtml::hiddenField('type', 'route');
			echo CHtml::hiddenField('name', $imported['name']);
			echo CHtml::hiddenField('desc', $imported['desc']);
			echo CHtml::hiddenField('points', implode('|', $points));
			echo CHtml::submitButton('Εισαγωγή');
			echo CHtml::endForm();
			
			echo '</tr></td>';
		}
		
		foreach ($imported['markers'] as $marker)
		{
			echo '<tr><td>';
			echo 'Σημείο ενδιαφέροντος με όνομα <b>' . $marker['name'] . '</b>';
			echo ' (' . googleMapLink('εμφ', $marker['lat'], $marker['lon']) . ')';
			echo '</td><td>';
			
			echo CHtml::beginForm($this->createUrl('import'), 'post');
			echo CHtml::hiddenField('type', 'point');
			echo CHtml::hiddenField('name', $marker['name']);
			echo CHtml::hiddenField('desc', $marker['desc']);
			echo CHtml::hiddenField('lat', $marker['lat']);
			echo CHtml::hiddenField('lon', $marker['lon']);
			echo CHtml::submitButton('Εισαγωγή');
			echo CHtml::endForm();
			
			echo '</tr></td>';
		}
	?>
</table>
