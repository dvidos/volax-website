/**
 * Assert the namespace
 */
var VOLAX = VOLAX || {};
VOLAX.Mapping = VOLAX.Mapping || {};


/**
 * A could-someday-be waypoint in the map.
 */
VOLAX.Mapping.PointOfInterest = function(options)
{
	// constructor for properties
	options = options || {};
	this.locationId = options.locationId || 0;
	this.lon = options.lon || 0; // in degrees, proper coordinates.
	this.lat = options.lat || 0;
	this.caption = options.caption || '';
	this.url = options.url || 0;
};
VOLAX.Mapping.PointOfInterest.prototype = 
{
	CLASSNAME: 'VOLAX.Mapping.PointOfInterest',
	
	fromAjaxData: function(ajaxData)
	{
		this.locationId = Number(ajaxData.locationId);
		this.lon = Number(ajaxData.lon);
		this.lat = Number(ajaxData.lat);
		this.caption = String(ajaxData.caption);
		this.url = String(ajaxData.url);
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};



