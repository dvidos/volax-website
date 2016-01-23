<?php

class GeoFileConverter extends CApplicationComponent
{
	public function init()
	{
		$poa = Yii::getPathOfAlias('application.components.GeoFileConverter');
		//$this->publish_path = Yii::app()->assetManager->publish($poa);
		//Yii::app()->clientScript->registerCssFile($this->publish_path . '/content-processor.css');
		
		// require_once(dirname(__FILE__).'/ContentProcessor/GpxConverter.php');
		// require_once(dirname(__FILE__).'/GeoFileConverter/KmzConverter.php');
		// require_once(dirname(__FILE__).'/GeoFileConverter/KmlConverter.php');
		
		parent::init();
	}
	
	public function getSupportedFormats()
	{
		return array('GPX');
	}
	
	/**
	 * should return a GeoFeature object
	 * treat depending on extension of real filename
	 * returns an array of the format = array(
	 * );
	 */
	public function importFile($uploadedFile)
	{
		$xml = simplexml_load_file($uploadedFile->tempName);
		if ($xml === false)
			throw new Exception('Failed loading XML from file');
		
		$imported = array(
			'filename' => $uploadedFile->name,
			'name' => (string)$xml->metadata->name,
			'desc' => (string)$xml->metadata->desc,
			'markers' => array(),
			'waypoints' => array(),
		);
		
		// markers
		foreach ($xml->wpt as $waypoint)
		{
			$marker = array(
				'lat'=>(string)$waypoint['lat'],
				'lon'=>(string)$waypoint['lon'],
				'name'=>(string)$waypoint->name,
				'desc'=>(string)$waypoint->desc,
			);
			$imported['markers'][] = $marker;
		}

		// track points (merge all segments)
		foreach ($xml->trk->children() as $segment)
		{
			if ($segment->getName() != 'trkseg')
				continue;
			
			foreach ($segment->children() as $point)
			{
				$waypoint = array(
					'lat'=>(string)$point['lat'],
					'lon'=>(string)$point['lon'],
				);
				$imported['waypoints'][] = $waypoint;
			}
		}
		
		return $imported;
	}
	
	/**
	 * should export a getFeature and send it to browser.
	 * format is a selection from getSupportedFormats()
	 * 
	 */
	public function exportFile($geoFeature, $format)
	{
	}
}




