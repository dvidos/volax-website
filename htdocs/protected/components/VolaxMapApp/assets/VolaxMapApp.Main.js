"use strict";
/**
 * Assert the namespace
 */
var VolaxMapApp = VolaxMapApp || {};


/**
 * The App object. 
 * Orchestrates changes between the HostAgent and the Map objects.
 * Also, renders some of the info on page.
 */
VolaxMapApp.Main = 
{
	// configurable options
	debugMode: true,
	readOnly: false,
	loadGeoGroupsUrl: '',
	loadGeoFeaturesUrl: '',
	saveGeoFeatureUrl: '',
	mapDivId: '',
	infoDivId: '',
	
	// runtime variables
	initialized: false,
	geoGroups: [],
	geoFeatures: [],
	map: null,
	host: null,
	
	initialize: function(options)
	{
		try
		{
			options = options || {};
			this.debugMode = options.debugMode || false;
			this.readOnly = options.readOnly || false;
			this.loadGeoGroupsUrl = options.loadGeoGroupsUrl || '';
			this.loadGeoFeaturesUrl = options.loadGeoFeaturesUrl || '';
			this.saveGeoFeatureUrl = options.saveGeoFeatureUrl || '';
			this.mapDivId = options.mapDivId || '';
			this.infoDivId = options.infoDivId || '';
			
			VolaxMapApp.Log.add('Initializing...');

			// prepare the info panel ids
			var html = 
				'<div id="volax-mapping-app-selection-info"></div>' +
				'<div id="volax-mapping-app-geoGroups"></div>';
			if (this.debugMode)
				html += '<h3>Debug log</h3><div id="volax-mapping-app-log" style="height: 10em; border: 1px solid #aaa; padding: .5em; overflow-y:scroll;"></div>';
			$('#' + this.infoDivId).html(html);
			
			if (this.debugMode)
				VolaxMapApp.Log.divId = 'volax-mapping-app-log';
			
			
			
			this.map = new VolaxMapApp.OpenLayersMap({
				divId: this.mapDivId,
				readOnly: this.readOnly,
				geoFeatures: this.geoFeatures,
			});
			
			// by google maps: 37.591793,25.1790143,17z
			this.map.render();
			this.map.setCenter(25.1790143, 37.591793);
			this.map.setZoom(17);

			this.loadGeoGroups();
			this.loadGeoFeatures();
			
			// this.host = new VOLAX.Mapping.HostAgent({
				// loadRouteUrl: this.loadRouteUrl,
				// saveRouteUrl: this.saveRouteUrl,
				// loadMarkersUrl: this.loadMarkersUrl,
				// loadPointsOfInterestUrl: this.loadPointsOfInterestUrl,
				// loadChangesUrl: this.loadChangesUrl,
				// saveChangesUrl: this.saveChangesUrl,
				// timeout: 2500,
			// });
			
			// this.host.routeLoaded.register(this, this.hostRouteLoaded);
			// this.host.markersLoaded.register(this, this.hostMarkersLoaded);
			// this.host.pointsOfInterestLoaded.register(this, this.hostPointsOfInterestLoaded);
			
			// // finally
			// this.host.loadRoute();
			// this.host.loadMarkers();
			// this.host.loadPointsOfInterest();
			// this.map.setMode(VOLAX.Mapping.MapModes.MOVE);
			
			
			
			
			
			
			
			VolaxMapApp.Log.add('Initialization finished!');
			this.initialized = true;
		}
		catch (e)
		{
			VolaxMapApp.Log.add('Initialization exception!');
			VolaxMapApp.Log.add(e);
			VolaxMapApp.Log.dump(e);
		}
	},
	
	hostRouteLoaded: function(ajaxData)
	{
		if (!this.initialized)
			return;
		
		VolaxMapApp.Log.add('host route has loaded');
		//VolaxMapApp.Log.dump(ajaxData, 'route');
		
		this.route.fromAjaxData(ajaxData);

		// show waypoints on map
		this.map.refreshDisplay(true);
		
		// center/zoom the map. this is the only time we shall center a map, to avoid annoying the user.
		// we should zoom to extend.
		this.map.zoomToRouteExtent();
		
		// show waypoints on list.
		this.renderWaypointsList();
		this.renderPointsOfInterestList();
		this.renderDistancesList();
		this.refreshNotes();
	},
	routeChangeHappened: function(routeChange)
	{
		if (!this.initialized)
			return;
		
		// VolaxMapApp.Log.dump(routeChange, 'a route change just happened');
		
		// this.host.addChange(routeChange.toAjaxData());
		
		// i decided to send right away, in order to minimize collisions with late sending.
		this.host.sendChangeNow(routeChange.toAjaxData());
		
		this.renderWaypointsList();
		this.renderPointsOfInterestList();
		this.renderDistancesList();
	},
	hostChangesAvailable: function(jsonArray)
	{
		if (!this.initialized)
			return;
		
		VolaxMapApp.Log.add('host changes are available');
		
		for (var i in jsonArray)
		{
			var routeChange = new VOLAX.Mapping.RouteChange();
			routeChange.fromAjaxData(jsonArray[i]);
			routeChange.applyToRoute(this.route, true);
		}
		
		this.map.refreshDisplay(false);
		this.renderWaypointsList();
		this.renderDistancesList();
		this.renderPointsOfInterestList();
	},
	hostMarkersLoaded: function(jsonArray)
	{
		if (!this.initialized)
			return;
		
		VolaxMapApp.Log.add('host markers are loaded');
		
		this.map.applyMarkers(jsonArray);
	},
	hostPointsOfInterestLoaded: function(jsonArray)
	{
		if (!this.initialized)
			return;
		
		VolaxMapApp.Log.add('host points of interest are loaded');
		
		this.pointsOfInterest = jsonArray;
		this.renderPointsOfInterestList();
		
		// cause a map refresh.
		this.map.pointsOfInterest = jsonArray;
		this.map.showHidePoisOnMap(this.map.showPoisOnMap);
	},
	
	loadGeoGroups: function() {
		$.ajax({
			url: this.loadGeoGroupsUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			data: { },
			success: function(data, textStatus, jqXHR)
			{
				// we are expecting an array
				if (data.length > 0)
				{
					if (this.debugMode) {
						VolaxMapApp.Log.add('loadGeoGroups(): ajax success, data follows');
						VolaxMapApp.Log.dump(data);
					}
					
					this.geoGroupsLoaded(data);
				}
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VolaxMapApp.Log.add('loadGeoGroups(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VolaxMapApp.Log.add('loadGeoGroups(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	geoGroupsLoaded: function(data) {
		this.geoGroups = data;
		this.renderGeoGroups();
	},
	
	loadGeoFeatures: function() {
		$.ajax({
			url: this.loadGeoFeaturesUrl,
			type: 'GET',
			cache: false,
			context: this,
			dataType: 'json',
			data: { },
			success: function(data, textStatus, jqXHR)
			{
				// we are expecting an array
				if (data.length > 0)
				{
					VolaxMapApp.Log.add('loadGeoFeatures(): ajax success, data follows');
					VolaxMapApp.Log.dump(data);
					
					this.geoFeaturesLoaded(data);
				}
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				VolaxMapApp.Log.add('loadGeoFeatures(): ajax error, textStatus="' + textStatus + '" errorThrown="' + errorThrown + '"');
				VolaxMapApp.Log.add('loadGeoFeatures(): jqXHR.responseText = "' + jqXHR.responseText + '"');
			},
		});
	},
	
	geoFeaturesLoaded: function(data) {
		this.geoFeatures = [];
		for (var i in data)
		{
			var gf = new VolaxMapApp.GeoFeature();
			gf.fromAjaxData(data[i]);
			this.geoFeatures.push(gf);
		}
		// should direct them to the map
		this.map.geoFeatures = this.geoFeatures;
		this.map.refreshDisplay(true);
	},
	
	
	
	
	
	renderGeoGroups: function() {
		var html = '';
		html += '<h3>Εμφάνιση</h3>';
		for (var i in this.geoGroups)
		{
			html += '<input type="checkbox" id="x" name="x" value="1" onClick="" checked="on" /> ' + this.geoGroups[i].title + '<br />';
		}
		$('#volax-mapping-app-geoGroups').html(html);
	},
	
	renderSelectionInfo: function(geoFeature) {
		var html = '';
		html += '<h3>Επιλογή</h3>';
		$('#volax-mapping-app-selection-info').html(html);
	},
	
};

