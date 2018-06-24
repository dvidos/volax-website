<?php


class VolaxMapAppWidget extends CWidget
{
	// in debug mode, fresh .js files are always copied to assets, not cached.
	public $debugMode = false;

	// whether to allow the user to make changes
	public $readOnly = true;
	
	// url to load data for the map
	public $loadGeoGroupsUrl = '';
	
	// url to load data for the map
	public $loadGeoFeaturesUrl = '';
	
	// url to load data for the map
	public $saveGeoFeatureUrl = '';
	
	// the div id for the map
	public $mapDivId = '';
	
	// the div id for the current selection info
	public $infoDivId = '';
	
	
	
	
	protected $assetsUrl = null;
	protected $cssFiles = array(
		'editor.css',
		'theme/default/style.css',
	);
	
	protected $externalJsFiles = array(
		'http://maps.google.com/maps/api/js?v=3&sensor=false&language=en',
	);
	
	protected $jsFiles = array(
		'VolaxMapApp.Event.js',
		'VolaxMapApp.Log.js',
		'VolaxMapApp.GeoFeature.js',
		'VolaxMapApp.OpenLayersMap.js',
		'VolaxMapApp.Main.js',
	);
	
	public function init()
	{
		// depending on debug mode.
		array_unshift($this->jsFiles, $this->debugMode ? 'OpenLayers.debug.js' : 'OpenLayers.js');
		
		// find the assets url (if not publish it)
		if ($this->assetsUrl === null)
		{
			$basePath = Yii::getPathOfAlias('application.components.VolaxMapApp.assets');
			$this->assetsUrl = Yii::app()->assetManager->publish($basePath, false, -1, $this->debugMode);
		}
		
		// register necessary scripts and css
		foreach($this->cssFiles as $cssFile)
			Yii::app()->clientScript->registerCssFile($this->assetsUrl . '/' . $cssFile);

		// we need this as well...
		Yii::app()->clientScript->registerCoreScript('jquery');
		
		foreach($this->externalJsFiles as $scriptUrl)
			Yii::app()->clientScript->registerScriptFile($scriptUrl);

		foreach($this->jsFiles as $scriptFile)
			Yii::app()->clientScript->registerScriptFile($this->assetsUrl . '/' . $scriptFile);

		parent::init();
	}

	
	public function run()
	{
		// register our takeoff!
		Yii::app()->clientScript->registerScript($this->id, 'VolaxMapApp.Main.initialize(' . CJavaScript::encode(array(
			'debugMode'=>$this->debugMode,
			'readOnly'=>$this->readOnly,
			'loadGeoGroupsUrl'=>$this->loadGeoGroupsUrl,
			'loadGeoFeaturesUrl'=>$this->loadGeoFeaturesUrl,
			'saveGeoFeatureUrl'=>$this->saveGeoFeatureUrl,
			'mapDivId'=>$this->mapDivId,
			'infoDivId'=>$this->infoDivId,
		)) . ');');
	}
}



?>