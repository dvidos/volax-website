
<div id="map-container">
	<div id="map">
	</div>
</div>
<div id="info-container">
	<div id="info-panel">
		here
	</div>
</div>
<div style="clear:both;"></div>


	
	<?php
		$this->widget('application.components.VolaxMapApp.VolaxMapAppWidget', array(
			
			// whether to be versbose and load clear-text openlayers
			'debugMode'=>false,
			
			// allow user to make changes?
			'readOnly'=>false,
			
			// url used for loading of all groups
			'loadGeoGroupsUrl'=>$this->createUrl('loadGeoGroups'),
			
			// url used for loading data. request will contain the screen edges and maybe a max records.
			'loadGeoFeaturesUrl'=>$this->createUrl('loadGeoFeatures'),
			
			// url used for saving a change in location, maybe a new point.
			'saveGeoFeatureUrl'=>$this->createUrl('saveGeoFeature'),
			
			// div for presenting the map
			'mapDivId'=>'map',
			
			// div for presenting info on current selection
			'infoDivId'=>'info-panel',
		));
	?>
