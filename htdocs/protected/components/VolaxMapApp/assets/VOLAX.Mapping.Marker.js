/**
 * Assert the namespace
 */
var VOLAX = VOLAX || {};
VOLAX.Mapping = VOLAX.Mapping || {};


/**
 * A marker in the map.
 */
VOLAX.Mapping.Marker = function()
{
	// constructor for properties
	this.iconFilename = '';
	this.caption = '';
	this.url = '';
	this.lon = 0; // proper coordinates in degrees, not meters
	this.lat = 0;
	this.comment = '';
	this.feature; // runtime feature
};
VOLAX.Mapping.Marker.prototype = 
{
	CLASSNAME: 'VOLAX.Mapping.Marker',
	
	fromAjaxData: function(ajaxData)
	{
		this.iconFilename = data.iconFilename;
		this.caption = data.caption;
		this.lon = data.lon;
		this.lat = data.lat;
		this.comment = data.comment;
		this.feature = null;
	},
	toString: function()
	{
		return this.CLASSNAME;
	},
};

