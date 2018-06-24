<?php

class MapController extends Controller
{
	// presents an interactive map, similar to maps.google.com
	public function actionIndex()
	{
		$this->layout = 'mapMainLayout';
		
		$this->render('index',array(
		));
	}
	
	// we shall need some ajax functions for dynamic loading and possibly for editing...
	
	
	
	/**
	 * Ajax call, called from VolaxMapApp
	 */
	public function actionLoadGeoGroups()
	{
		$groups = GeoGroup::model()->findAll(array(
			'condition'=>'active = 1',
			'order'=>'view_order, title',
		));
		
		$ajax_array = array();
		foreach ($groups as $group)
			$ajax_array[] = $group->toAjaxAssoc();
		
		echo CJSON::encode($ajax_array);
	}
	
	/**
	 * Ajax call, called from VolaxMapApp
	 */
	public function actionLoadGeoFeatures()
	{
		$features = GeoFeature::model()->findAll(array(
			'condition'=>'active = 1',
			'order'=>'id',
		));
		
		$ajax_array = array();
		foreach ($features as $feature)
			$ajax_array[] = $feature->toAjaxAssoc();
		
		echo CJSON::encode($ajax_array);
	}
}
