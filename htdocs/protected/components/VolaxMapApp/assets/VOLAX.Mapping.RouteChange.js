/**
 * Assert the namespace
 */
var VOLAX = VOLAX || {};
VOLAX.Mapping = VOLAX.Mapping || {};


/**
 * A change that happened, somewhere, sometime.
 * Warning! If you change these, you also have to change the same in custapp/protected/models/RouteChange.php
 */
VOLAX.Mapping.RouteChangeTypes =
{
	ROUTE_COMMENT_CHANGED: 1,
	ROUTE_MAP_POSITIONED: 2,
	
	// see newValue, a JSON object added
	WAYPOINT_ADDED: 3,
	
	// see listIndex, the index that changed, newValue has JSON object of waypoint
	WAYPOINT_CHANGED: 4,
	
	// see listIndex, the index that was deleted
	WAYPOINT_DELETED: 5,
	
	// re-ordering of waypoints.
	WAYPOINT_MOVED_UP: 6,
	WAYPOINT_MOVED_DOWN: 7,
	
	// used when a mid-point is promoted to waypoint.
	WAYPOINT_INSERTED: 8,
	
	// when user changes the "is-library-item" checkbox.
	ROUTE_LIBRARY_ITEM_CHANGED: 9,
};
VOLAX.Mapping.RouteChange = function(options)
{
	options = options || {};
	this.changeType = options.changeType || 0;
	this.listIndex = typeof options.listIndex == 'number' ? options.listIndex : (-1);
	this.newValue = options.newValue || null;
	this.oldValue = options.oldValue || null;
};
VOLAX.Mapping.RouteChange.prototype = 
{
	CLASSNAME: 'VOLAX.Mapping.RouteChange',
	
	toAjaxData: function()
	{
		return {
			changeType: this.changeType,
			listIndex: this.listIndex,
			newValue: this.newValue,
			oldValue: this.oldValue,
		};
	},
	fromAjaxData: function(ajaxData)
	{
		this.changeType = ajaxData.changeType;
		this.listIndex = ajaxData.listIndex;
		this.newValue = ajaxData.newValue;
		this.oldValue = ajaxData.oldValue;
	},
	toString: function()
	{
		return this.CLASSNAME + ', type:' + this.changeType + ', index:' + this.listIndex + ', newValue:' + JSON.stringify(this.newValue) + ', oldValue:' + JSON.stringify(this.oldValue);
	},
	
	/**
	 * applyToRoute() is used to apply changes made by other users
	 */
	applyToRoute: function(route, silently)
	{
		switch (Number(this.changeType))
		{
			case VOLAX.Mapping.RouteChangeTypes.ROUTE_COMMENT_CHANGED:
				route.setComment(this.newValue.comment, silently);
				break;
			
			case VOLAX.Mapping.RouteChangeTypes.ROUTE_MAP_POSITIONED:
				// we ignore such events.
				// route.positionMap(this.newValue.centerLon, this.newValue.centerLat, this.newValue.zoom, silently);
				break;
			
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_ADDED:
				var wp = new VOLAX.Mapping.Waypoint();
				wp.fromAjaxData(this.newValue);
				route.addWaypoint(wp, silently);
				break;
			
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_CHANGED:
				// should verify old waypoint
				route.waypoints[this.listIndex].fromAjaxData(this.newValue);
				if (!silently)
					route.notifyWaypointChanged(this.listIndex);
				break;
			
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_DELETED:
				// should verify old waypoint
				route.removeWaypoint(this.listIndex, silently);
				break;
			
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_MOVED_UP:
				// should verify old waypoint
				route.moveWaypointUp(this.listIndex, silently);
				break;
				
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_MOVED_DOWN:
				// should verify old waypoint
				route.moveWaypointDown(this.listIndex, silently);
				break;
				
			case VOLAX.Mapping.RouteChangeTypes.WAYPOINT_INSERTED:
				var wp = new VOLAX.Mapping.Waypoint();
				wp.fromAjaxData(this.newValue);
				route.insertWaypoint(wp, this.listIndex, silently);
				break;
			
			default:
				VOLAX.Log.add('Warning!!! unknown route change type ' + this.changeType + ', change has not been applied');
				break;
		}
	},
};


