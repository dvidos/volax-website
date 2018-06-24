/**
 * Assert the namespace
 */
var VOLAX = VOLAX || {};
VOLAX.Mapping = VOLAX.Mapping || {};

/**
 * A route in the map.
 */
VOLAX.Mapping.Route = function()
{
	// constructor for properties
	this.title;
	this.comment;
	this.centerLon = 0;
	this.centerLat = 0;
	this.zoom = 10;
	this.lastChangeId = 0;
	this.waypoints = [];
	this.isLibraryItem = false;

	this.beforeRemoveWaypoint = new VOLAX.Event();
	this.changeHappened = new VOLAX.Event();
};
VOLAX.Mapping.Route.prototype = 
{
	CLASSNAME: 'VOLAX.Mapping.Route',
	
	toAjaxData: function()
	{
		var jsonWaypoints = [];
		for (x in this.waypoints)
			jsonWaypoints.push(this.waypoints[x].toAjaxData());
		
		return {
			title: this.title,
			comment: this.comment,
			centerLon: this.centerLon,
			centerLat: this.centerLat,
			zoom: this.zoom,
			// we do not send lastChangedId, we are sending our changes.
			waypoints: jsonWaypoints,
		};
	},
	
	fromAjaxData: function(ajaxData)
	{
		this.title = String(ajaxData.title);
		this.comment = String(ajaxData.comment);
		this.centerLon = Number(ajaxData.centerLon);
		this.centerLat = Number(ajaxData.centerLat);
		this.zoom = Number(ajaxData.zoom);
		this.isLibraryItem = (Number(ajaxData.isLibraryItem) != 0);
		
		this.waypoints = [];
		for (x in ajaxData.waypoints)
		{
			wp = new VOLAX.Mapping.Waypoint();
			wp.fromAjaxData(ajaxData.waypoints[x]);
			this.waypoints.push(wp);
		}
	},
	
	indexOfFeature: function(feature)
	{
		for (var i in this.waypoints)
			if (this.waypoints[i].feature == feature)
				return Number(i);
		
		return -1;
	},
	
	indexOfMidFeature: function(feature)
	{
		for (var i in this.waypoints)
			if (this.waypoints[i].midFeature == feature)
				return Number(i);
		
		return -1;
	},
	
	positionMap: function(centerLon, centerLat, zoom, silently)
	{
		this.centerLon = centerLon;
		this.centerLat = centerLat;
		this.zoom = zoom;
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.ROUTE_MAP_POSITIONED,
				newValue: { centerLon: this.centerLon, centerLat: this.centerLat, zoom: this.zoom },
			}));
		}
	},
	
	setComment: function(comment, silently)
	{
		this.comment = comment;
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.ROUTE_COMMENT_CHANGED,
				newValue: { comment: comment },
			}));
		}
	},
	
	setIsLibraryItem: function(isLibraryItem, silently)
	{
		this.isLibraryItem = isLibraryItem;
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.ROUTE_LIBRARY_ITEM_CHANGED,
				newValue: { isLibraryItem: isLibraryItem ? 1 : 0},
			}));
		}
	},
	
	addWaypoint: function(waypoint, silently)
	{
		this.waypoints.push(waypoint);
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_ADDED,
				newValue: waypoint.toAjaxData(),
			}));
		}
	},
	
	addPredefinedWaypoint: function(lon, lat, title, url, locationId)
	{
		// create waypoint and add it to route.
		var wp = new VOLAX.Mapping.Waypoint({
			lon: lon,
			lat: lat,
			caption: title,
			url: url,
			locationId: locationId,
		});
		this.addWaypoint(wp);
	},
	
	locationIncludedInWaypoints: function(locationId)
	{
		for (var x in this.waypoints)
		{
			if (this.waypoints[x].locationId == locationId)
				return true;
		}
		return false;
	},
	
	insertWaypoint: function(waypoint, index, silently)
	{
		this.waypoints.splice(index, 0, waypoint);
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_INSERTED,
				listIndex: index,
				newValue: waypoint.toAjaxData(),
			}));
		}
	},
	
	moveWaypoint: function(index, newCoords, oldCoords, silently)
	{
		// waypoint has already moved to display the dragging on screen.
		// therefore we update the original coordinates before drag.
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		oldValue.lon = oldCoords.lon;
		oldValue.lat = oldCoords.lat;
		
		wp.lon = newCoords.lon;
		wp.lat = newCoords.lat;
		
		if (!silently)
			this.notifyWaypointChanged(index, oldValue);
	},
	
	setWaypointChangeDateHere: function(index, value, silently)
	{
		VOLAX.Log.add('setWaypointChangeDateHere(' + index + ', ' + value + ');');
		
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		
		wp.changeDateHere = value;
		
		if (!silently)
			this.notifyWaypointChanged(index, oldValue);
	},
	
	setWaypointComment: function(index, value, silently)
	{
		VOLAX.Log.add('setWaypointComment(' + index + ', ' + value + ');');
		
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		
		wp.comment = value;
		
		if (!silently)
			this.notifyWaypointChanged(index, oldValue);
	},
	
	notifyWaypointChanged: function(index, oldAjaxData)
	{
		this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
			changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_CHANGED,
			listIndex: index,
			oldValue: oldAjaxData,
			newValue: this.waypoints[index].toAjaxData(),
		}));
	},
	
	moveWaypointUp: function(index, silently)
	{
		if (index == 0 || index >= this.waypoints.length)
			return;
		
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		
		this.waypoints.splice(index, 1);
		this.waypoints.splice(index - 1, 0, wp);
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_MOVED_UP,
				listIndex: index,
				oldValue: oldValue,
			}));
		}
	},
	
	moveWaypointDown: function(index, silently)
	{
		if (index < 0 || index >= this.waypoints.length - 1)
			return;
		
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		
		this.waypoints.splice(index, 1);
		this.waypoints.splice(index + 1, 0, wp);
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_MOVED_DOWN,
				listIndex: index,
				oldValue: oldValue,
			}));
		}
	},
	
	removeWaypoint: function(index, silently)
	{
		var wp = this.waypoints[index];
		var oldValue = wp.toAjaxData();
		
		this.beforeRemoveWaypoint.fire(wp);
		this.waypoints.splice(index, 1);
		
		// notify of change
		if (!silently)
		{
			this.changeHappened.fire(new VOLAX.Mapping.RouteChange({
				changeType: VOLAX.Mapping.RouteChangeTypes.WAYPOINT_DELETED,
				listIndex: index,
				oldValue: oldValue,
			}));
		}
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};

