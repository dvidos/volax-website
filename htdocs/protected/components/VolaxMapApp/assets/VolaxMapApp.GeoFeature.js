/**
 * Assert the namespace
 */
var VolaxMapApp = VolaxMapApp || {};


/**
 * A geo feature.
 */
VolaxMapApp.GeoFeature = function(options)
{
	// constructor for properties
	options = options || {};
	
	this.id = options.id || 0;
	this.featureType = options.featureType || 'point';
	this.groupId = options.groupId || 0;
	this.title = options.title || '';
	this.description = options.description || '';
	this.geoLong = options.geoLong || 0; // in degrees, proper coordinates.
	this.geoLat = options.geoLat || 0;
	this.waypoints = options.waypoints || [];
	
	// runtime bookkeeping variables.
	this.olMapFeature = options.olMapFeature || null;
};
VolaxMapApp.GeoFeature.prototype = 
{
	CLASSNAME: 'VolaxMapApp.GeoFeature',
	
	toAjaxData: function()
	{
		return {
			id: this.id,
			featureType: this.featureType,
			groupId: this.groupId,
			title: this.title,
			description: this.description,
			geoLong: this.geoLong,
			geoLat: this.geoLat,
			waypoints: this.waypoints,
		};
	},
	
	fromAjaxData: function(ajaxData)
	{
		this.id = Number(ajaxData.id);
		this.featureType = ajaxData.featureType;
		this.groupId = Number(ajaxData.groupId);
		this.title = ajaxData.title;
		this.description = ajaxData.description;
		this.geoLong = Number(ajaxData.geoLong);
		this.geoLat = Number(ajaxData.geoLat);
		this.waypoints = ajaxData.waypoints;
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};



