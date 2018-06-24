/**
 * Assert the namespace
 */
var VOLAX = VOLAX || {};
VOLAX.Mapping = VOLAX.Mapping || {};


/**
 * The HostAgent object. Works with the host.
 * Sends and receives the route and changes to it.
 */
VOLAX.Mapping.HostAgent = function(options)
{
	options = options || {};
	
	// eg: localhost/saillavie/plan/loadRoute?routeId=15
	
	this.saveRouteUrl = options.saveRouteUrl || '';
	this.loadRouteUrl = options.loadRouteUrl || '';
	this.loadMarkersUrl = options.loadMarkersUrl || '';
	this.loadPointsOfInterestUrl = options.loadPointsOfInterestUrl || '';
	this.saveChangesUrl = options.saveChangesUrl || '';
	this.loadChangesUrl = options.loadChangesUrl || '';

	
	this.outgoingChanges = [];
	this.inboxChanges = [];
	this.lastChangeId = 0;
	this.sessionGuid = '';
	
	this.routeLoaded = new VOLAX.Event();
	this.markersLoaded = new VOLAX.Event();
	this.pointsOfInterestLoaded = new VOLAX.Event();
	this.changesAvailable = new VOLAX.Event();
	
	// for clearInterval()
	this.saveChangesTimerHandle = null;
	this.loadChangesTimerHandle = null;
};
VOLAX.Mapping.HostAgent.prototype = 
{
	CLASSNAME: 'VOLAX.Mapping.HostAgent',
	
	loadRoute: function()
	{
		if (typeof this.loadRouteUrl != 'string' || this.loadRouteUrl.length == 0)
			return;
		
		VOLAX.Log.add('HostAgent.loadRoute(): Loading route from ' + this.loadRouteUrl);
		
		// should load and on success fire the routeLoaded event.
		$.ajax({
			url: this.loadRouteUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				this.routeLoaded.fire(data);
				
				// if possible, keep the lastChangeId of the route and the session id for changes.
				if (data.lastChangeId)
					this.lastChangeId = data.lastChangeId;
				if (data.sessionGuid)
					this.sessionGuid = data.sessionGuid;
					
				VOLAX.Log.add('HostAgent.loadRoute(): success, lastChangeId = ' + this.lastChangeId + ', sessionGuid = ' + this.sessionGuid);
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.loadRoute(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VOLAX.Log.add('HostAgent.loadRoute(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	saveRoute: function(ajaxData)
	{
		if (typeof this.saveRouteUrl != 'string' || this.saveRouteUrl.length == 0)
			return;
		
		VOLAX.Log.add('HostAgent.saveRoute(): saving route to ' + this.saveRouteUrl);
		
		// should use ajax to save the route
		$.ajax({
			url: this.saveRouteUrl,
			type: 'POST',
			dataType: 'json',
			data: { route: ajaxData, },
			context: this,
			success: function(data, textStatus, jqXHR)
			{
				// nothing?
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.saveRoute(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
			},
		});
	},
	
	loadMarkers: function()
	{
		if (typeof this.loadMarkersUrl != 'string' || this.loadMarkersUrl.length == 0)
			return;
	
		VOLAX.Log.add('HostAgent.loadMarkers(): loading markers from ' + this.loadMarkersUrl);
		
		// should load and on success fire the routeLoaded event.
		$.ajax({
			url: this.loadMarkersUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				this.markersLoaded.fire(data);
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.loadMarkers(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
			},
		});
	},
	
	loadPointsOfInterest: function()
	{
		if (typeof this.loadPointsOfInterestUrl != 'string' || this.loadPointsOfInterestUrl.length == 0)
			return;
	
		VOLAX.Log.add('HostAgent.loadPointsOfInterest(): loading markers from ' + this.loadPointsOfInterestUrl);
		
		// should load and on success fire the routeLoaded event.
		$.ajax({
			url: this.loadPointsOfInterestUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				this.pointsOfInterestLoaded.fire(data);
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.loadPointsOfInterest(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
			},
		});
	},
	
	addChange: function(ajaxData)
	{
		// VOLAX.Log.dump(ajaxData, 'HostAgent.addChange(): ajaxData');
		
		this.outgoingChanges.push(ajaxData);
		
		// VOLAX.Log.add('HostAgent.addChange() queue now has ' + this.outgoingChanges.length + ' items');
	},

	sendChangeNow: function(ajaxData)
	{
		// VOLAX.Log.add('HostAgent.sendChangeNow(), saving to ' + this.saveChangesUrl);
		
		// do not know the url
		if (!this.saveChangesUrl || this.saveChangesUrl.length == 0)
			return;
		
		// have not loaded a route yet.
		if (this.sessionGuid.length == 0)
			return;
		
		// call ajax to send any pooled changes. on success clear the array.
		$.ajax({
			url: this.saveChangesUrl,
			type: 'POST',
			dataType: 'json',
			data: {changes: [ ajaxData ], sessionGuid: this.sessionGuid, },
			context: this,
			success: function(data, textStatus, jqXHR)
			{
				VOLAX.Log.add('HostAgent.sendChangeNow(): success, got data: ' + JSON.stringify(data));
				
				// if a change failed, we should load the route.
				if (data.status != 'OK')
					this.loadRoute();
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.sendChangeNow(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VOLAX.Log.add('HostAgent.sendChangeNow(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	sendOutgoingChanges: function()
	{
		// VOLAX.Log.add('HostAgent.sendOutgoingChanges(), queue size is ' + this.outgoingChanges.length + ', saving to ' + this.saveChangesUrl);
		
		// do not know the url
		if (!this.saveChangesUrl || this.saveChangesUrl.length == 0)
			return;
		
		// have not loaded a route yet.
		if (this.sessionGuid.length == 0)
			return;
		
		// have no changes to send.
		if (this.outgoingChanges.length == 0)
			return;
		
		// call ajax to send any pooled changes. on success clear the array.
		$.ajax({
			url: this.saveChangesUrl,
			type: 'POST',
			dataType: 'json',
			data: {changes: this.outgoingChanges, sessionGuid: this.sessionGuid, },
			context: this,
			success: function(data, textStatus, jqXHR)
			{
				VOLAX.Log.add('HostAgent.sendOutgoingChanges(): success, sent ' + this.outgoingChanges.length + ' changes, clearing queue');
				
				this.outgoingChanges = [];
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.sendOutgoingChanges(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VOLAX.Log.add('HostAgent.sendOutgoingChanges(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	pollIncomingChanges: function()
	{
		// VOLAX.Log.add('HostAgent.pollIncomingChanges() loading from ' + this.loadChangesUrl);
		
		// do not know the url.
		if (!this.loadChangesUrl || this.loadChangesUrl.length == 0)
			return;
		
		// have not loaded a route yet.
		if (this.sessionGuid.length == 0)
			return;
		
		// call ajax to see if any changes from last time. in success, call the event "changesAvailable"
		$.ajax({
			url: this.loadChangesUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			data: { lastChangeId: this.lastChangeId, sessionGuid: this.sessionGuid, },
			success: function(data, textStatus, jqXHR)
			{
				// we are expecting an array
				if (data.length > 0)
				{
					this.changesAvailable.fire(data);
				
					// if at all possible, keep the id of the last change
					this.lastChangeId = data[data.length - 1].id;
					
					VOLAX.Log.add('HostAgent.pollIncomingChanges(): ajax success, got ' + data.length + ' changes, new lastChangeId = ' + this.lastChangeId);
				}
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VOLAX.Log.add('HostAgent.pollIncomingChanges(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VOLAX.Log.add('HostAgent.pollIncomingChanges(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};

