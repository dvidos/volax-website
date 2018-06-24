"use strict";
/**
 * Assert the namespace
 */
var VolaxMapApp = VolaxMapApp || {};


/**
 * The Map object. Encapsulates and manipulates an OpenLayers olMap.
 * It only covers the olMap area. It bridges the OpenLayers olMap and our geo features
 */
VolaxMapApp.MapModes = 
{
	MOVE: 1,
	ADD: 2,
	DELETE: 3,
};
VolaxMapApp.Projections = 
{
	// used for transformations.
	degrees: new OpenLayers.Projection("EPSG:4326"),
	google: new OpenLayers.Projection("EPSG:900913"),
};
VolaxMapApp.OpenLayersMap = function(options)
{
	options = options || {};
	this.mode = options.mode || VolaxMapApp.MapModes.MOVE;
	this.divId = options.divId || '';
	this.readOnly = options.readOnly || false;
	this.geoFeatures = options.geoFeatures || [];
	
	this.handlers = [];
	this.ignoreEvents = false;
	this.showPoisOnMap = false;
	
	// the OpenLayers olMap object.
	this.olMap = new OpenLayers.Map({
		div: this.divId,
		projection: VolaxMapApp.Projections.google,
		displayProjection: VolaxMapApp.Projections.degrees,
		numZoomLevels: 22,
	});
	
	// used for... drawing and manipulations!
	this.vectorLayer = null;
	//this.routeLineFeature = null;
	this.drawFeatureControl = null;
	this.selectFeatureControl = null;
	this.dragFeatureControl = null;
	this.dragStartCoords = null;

	this.modeChanged = new VolaxMapApp.Event();
};
VolaxMapApp.OpenLayersMap.prototype = 
{
	CLASSNAME: 'VolaxMapApp.OpenLayersMap',
	
	render: function()
	{
		// add a few layers.
		var gsat = new OpenLayers.Layer.Google("Google Satellite",{type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22});
		var gphy = new OpenLayers.Layer.Google("Google Physical", {type: google.maps.MapTypeId.TERRAIN});
		var ghyb = new OpenLayers.Layer.Google("Google Hybrid", {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});
		var gmap = new OpenLayers.Layer.Google("Google Streets", {numZoomLevels: 20});
		var osml = new OpenLayers.Layer.OSM();
		this.olMap.addLayers([ gsat, gphy, ghyb, gmap, osml ]);
		
		// add the vector layer
		this.vectorLayer = new OpenLayers.Layer.Vector("Vector", {
			projection: VolaxMapApp.Projections.degrees,
			rendererOptions: { zIndexing: true }, 
		});
		this.olMap.addLayer(this.vectorLayer);
		
		// add a few controls to the olMap
		this.olMap.addControl(new OpenLayers.Control.LayerSwitcher());
		// this.olMap.addControl(new OpenLayers.Control.Permalink());
		// this.olMap.addControl(new OpenLayers.Control.MousePosition());
		// this.olMap.addControl(new OpenLayers.Control.PanZoomBar());
		// this.olMap.addControl(new OpenLayers.Control.ScaleLine());

		this.olMap.events.register('moveend', this, this.onMapMovedZoomed);
		
		this.drawFeatureControl = new OpenLayers.Control.DrawFeature(this.vectorLayer, OpenLayers.Handler.Point);
		this.vectorLayer.events.register('featureadded', this, this.onMapFeatureInserted);
		// this.drawFeatureControl.events.register('featureadded', this, this.onMapFeatureInserted);
		
		this.selectFeatureControl = new OpenLayers.Control.SelectFeature(this.vectorLayer);
		this.vectorLayer.events.register('featureselected', this, this.onMapFeatureHighlighted);
		this.vectorLayer.events.register('featureunselected', this, this.onMapFeatureUnhighlighted);
		
		var self = this;
		this.dragFeatureControl = new OpenLayers.Control.DragFeature(this.vectorLayer, {
			geometryTypes: ['OpenLayers.Geometry.Point'],
			overFeature: function(feature){ self.onMapDragControlOver(feature); },
			onEnter: function(feature){ self.onMapDragControlEnter(feature); },
			onStart: function(feature){ self.onMapDragControlStart(feature); },
			onDrag: function(feature){ self.onMapDragControlDrag(feature); },
			onComplete: function(feature){ self.onMapDragControlComplete(feature); },
		});
		
		this.olMap.addControl(this.drawFeatureControl);
		this.olMap.addControl(this.selectFeatureControl);
		this.olMap.addControl(this.dragFeatureControl);
		
		// initial state
		this.setMode(VolaxMapApp.MapModes.MOVE);
		
		// this causes display
		this.olMap.zoomToMaxExtent();		
	},
	
	getCenter: function()
	{
		var lonlat = this.olMap.center.clone();
		lonlat.transform(VolaxMapApp.Projections.google, VolaxMapApp.Projections.degrees);
		return {'lon': lonlat.lon, 'lat': lonlat.lat };
	},
	
	setCenter: function(lon, lat)
	{
		this.ignoreEvents = true;
		
		var lonlat = new OpenLayers.LonLat(lon, lat);
		lonlat.transform(VolaxMapApp.Projections.degrees, VolaxMapApp.Projections.google);
		this.olMap.setCenter(lonlat);
		
		this.ignoreEvents = false;
	},
	
	getZoom: function()
	{
		return this.olMap.zoom;
	},
	
	setZoom: function(zoom)
	{
		this.ignoreEvents = true;
		this.olMap.zoomTo(zoom);
		this.ignoreEvents = false;
	},
	
	setMode: function(mode)
	{
		// can have move + activate at the same time.
		// only draw needs to be different.
		// we need this to implement ability to click on markers for info, while editing the route.
		
		if (mode == VolaxMapApp.MapModes.MOVE)
		{
			this.mode = VolaxMapApp.MapModes.MOVE;
			this.drawFeatureControl.deactivate();
			this.dragFeatureControl.activate();
			this.selectFeatureControl.activate();
			this.modeChanged.fire(this);
		}
		else if (mode == VolaxMapApp.MapModes.ADD)
		{
			this.mode = VolaxMapApp.MapModes.ADD;
			this.drawFeatureControl.activate();
			this.dragFeatureControl.deactivate();
			this.selectFeatureControl.deactivate();
			this.modeChanged.fire(this);
		}
		else if (mode == VolaxMapApp.MapModes.DELETE)
		{
			this.mode = VolaxMapApp.MapModes.DELETE;
			this.drawFeatureControl.deactivate();
			this.dragFeatureControl.deactivate();
			this.selectFeatureControl.activate();
			this.modeChanged.fire(this);
		}
	},
	
	getMode: function()
	{
		return this.mode;
	},
	
	// these are events generated by the OpenLayers olMap controls.

	onMapMovedZoomed: function()
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		var center = this.getCenter();
		// this.route.positionMap(center.lon, center.lat, this.getZoom());
	},
	
	onMapFeatureInserted: function(eventArg)
	{
		// // if we are set by code, we shouldn't propagate to route.
		// if (this.ignoreEvents)
			// return;
		
		// if (this.readOnly)
			// return;
		
		// // we must format it, add it to our route and generate a change to be published.
		// // VOLAX.Log.add('onMapFeatureInserted, ' + ((eventArg.feature) ? 'we have the feature' : 'we do not have a feature'));
		
		// if (this.mode == VolaxMapApp.MapModes.ADD)
		// {
			// // we are in insert mode and the user just clicked somewhere. we should add a waypoint.
			// var feature = eventArg.feature;
			// var coords = new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y);
			// coords.transform(VolaxMapApp.Projections.google, VolaxMapApp.Projections.degrees);
				
			// // keep 6 decimals, for it produces 12 and the rounding errors do not allow us to identify the waypoints.
			// coords.lon = Math.round(coords.lon * 1000000) / 1000000;
			// coords.lat = Math.round(coords.lat * 1000000) / 1000000;
			
			// feature.style = VolaxMapApp.MapStyles.waypointsStyle;
			// feature.attributes = { caption: '', };

			// // var wp = new VolaxMapApp.Waypoint({
				// // 'lon': coords.lon,
				// // 'lat': coords.lat,
				// // 'feature': feature,
			// // });
			// // this.route.addWaypoint(wp);
			
			// this.refreshDisplay();
		// }
	},
	
	onMapFeatureHighlighted: function(eventArg)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
			
		// VOLAX.Log.add('onMapFeatureHighlighted, ' + ((eventArg.feature) ? 'we have the feature' : 'we do not have a feature'));
		
		// try to see if this is a P.O.I.
		if (eventArg.feature && eventArg.feature.data.poi)
		{
			// it's a point of interest. present a popup.
			// we have we have: { lon, lat, caption, url, locationId, iconUrl }
			var poi = eventArg.feature.data.poi;
			var html = '<a href="' + poi.url + '">' + poi.caption + '</a>';
			var popup = new OpenLayers.Popup.FramedCloud(
				'popup',
				OpenLayers.LonLat.fromString(eventArg.feature.geometry.toShortString()),
                null, // content size
                html,
                null, // object to anchor to
                true, // close box ?
                null // close box callback
            );
            popup.autoSize = true;
            popup.maxSize = new OpenLayers.Size(400,800);
            popup.fixedRelativePosition = true;
			popup.backgroundColor = '#ffffff';
			popup.padding = 5;
            eventArg.feature.popup = popup;
			for (var i in this.olMap.popups)
				this.olMap.removePopup(this.olMap.popups[i]);
            this.olMap.addPopup(popup, true);
			
			// should unhighlight the feature, to allow for future click to re-open the popup.
			this.selectFeatureControl.unhighlight(eventArg.feature);
			return;
		}
		
		
		if (this.mode == VolaxMapApp.MapModes.DELETE && !this.readOnly)
		{
			// we are in delete mode and the user just clicked somewhere. we should delete the waypoint.
			//var index = this.route.indexOfFeature(eventArg.feature);
			var index = -1;
			// VOLAX.Log.add('onMapFeatureHighlighted(): index = ' + index);
			if (index > -1)
			{
				// finally, remove from our list.
				//this.route.removeWaypoint(index);
				
				// update the route line on olMap
				this.refreshDisplay();
			}
			
			// also, deleting is one-click-only function
			this.setMode(VolaxMapApp.MapModes.MOVE);
		}
	},
	
	onMapFeatureUnhighlighted: function(eventArg)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		// remove a possible popup.
		for (var i in this.olMap.popups)
			this.olMap.removePopup(this.olMap.popups[i]);
	},
	
	onMapDragControlOver: function(feature)
	{
		// VOLAX.Log.add('onMapDragControlOver, ' + ((feature) ? 'we have the feature' : 'we do not have a feature'));
		
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		var allow = !this.readOnly && this.isFeatureMovable(feature);
		if (allow)
			this.dragFeatureControl.handlers.drag.activate();
		
		
		
		// copied from http://jsfiddle.net/_DR_/sLfB9/. don't ask me how it works...
        var activated = false;
        if(!this.dragFeatureControl.handlers.drag.dragging)
		{
            this.dragFeatureControl.feature = feature;
            this.dragFeatureControl.handlers.drag.activate();
            activated = true;
            this.dragFeatureControl.over = true;
            if (allow)
                OpenLayers.Element.addClass(this.dragFeatureControl.map.viewPortDiv, this.dragFeatureControl.displayClass + "Over");
            this.dragFeatureControl.onEnter(feature);
        }
		else
		{
            this.dragFeatureControl.over = (this.dragFeatureControl.feature.id == feature.id);
        }
        return activated;
	},
	
	onMapDragControlEnter: function(feature)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		// VOLAX.Log.add('onMapDragControlEnter, ' + ((feature) ? 'we have the feature' : 'we do not have a feature'));
		
		var allow = !this.readOnly && this.isFeatureMovable(feature);
		if (!allow)
			this.dragFeatureControl.handlers.drag.deactivate();
	},
	
	onMapDragControlStart: function(feature)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return false;
		
		// VOLAX.Log.add('onMapDragControlStart, ' + ((feature) ? 'we have the feature' : 'we do not have a feature'));
		
		var allow = !this.readOnly && this.isFeatureMovable(feature);
		if (!allow)
			return false;
		
		// keep thie initial coordinates for the "waypointMoved" event...
		// var index = this.route.indexOfFeature(feature);
		// if (index == -1)
		// {
			// this.dragStartCoords = new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y);
			// this.dragStartCoords.transform(VolaxMapApp.Projections.google, VolaxMapApp.Projections.degrees);
		// }
		// else
		// {
			// var wp = this.route.waypoints[index];
			// this.dragStartCoords = new OpenLayers.LonLat(wp.lon, wp.lat);
		// }
		
		return allow;
	},
	
	onMapDragControlDrag: function(feature)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		if (this.readOnly)
			return;
		
		// VOLAX.Log.add('onMapDragControlDrag, ' + ((feature) ? 'we have the feature' : 'we do not have a feature'));
		
		if (this.mode == VolaxMapApp.MapModes.MOVE)
		{
			// // now update coords.
			// var ll = new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y);
			// ll.transform(VolaxMapApp.Projections.google, VolaxMapApp.Projections.degrees);
			// var index = this.route.indexOfFeature(feature);
			// this.route.waypoints[index].lon = ll.lon;
			// this.route.waypoints[index].lat = ll.lat;
			
			this.refreshDisplay();
		}
	},
	
	onMapDragControlComplete: function(feature)
	{
		// if we are set by code, we shouldn't propagate to route.
		if (this.ignoreEvents)
			return;
		
		if (this.readOnly)
			return;
		
		// VOLAX.Log.add('onMapDragControlComplete, ' + ((feature) ? 'we have the feature' : 'we do not have a feature'));
		
		if (this.mode == VolaxMapApp.MapModes.MOVE)
		{
			// notify of final coordinates
			//var index = this.route.indexOfFeature(feature);
			var index = -1;
			if (index != -1)
			{
				var targetCoords = new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y);
				targetCoords.transform(VolaxMapApp.Projections.google, VolaxMapApp.Projections.degrees);
				
				// keep 6 decimals, for it produces 12 and the rounding errors do not allow us to identify the waypoints.
				targetCoords.lon = Math.round(targetCoords.lon * 1000000) / 1000000;
				targetCoords.lat = Math.round(targetCoords.lat * 1000000) / 1000000;
				
				VOLAX.Log.add('moving waypoint ' + index + ' from ' + 
					'(' + this.dragStartCoords.lon + ', ' + this.dragStartCoords.lat + ')' + 
					' to ' +
					'(' + targetCoords.lon + ', ' + targetCoords.lat + ')');
					
				
				// this.dragStartCoords holds the original waypoint coordinates. 
				// this.route.moveWaypoint(index, targetCoords, this.dragStartCoords);
			}
		}
	},
	
	isFeatureMovable: function(feature)
	{
		return false;
		
		// is it a waypoint
		var wpIndex = this.route.indexOfFeature(feature);
		if (wpIndex > -1)
		{
			// only if not a fixed location.
			return (Number(this.route.waypoints[wpIndex].locationId) == 0);
		}
		
		// not a waypoint. could it be a mid-point?
		var midIndex = this.route.indexOfMidFeature(feature);
		return Boolean(midIndex > -1);
	},
	
	// called from the application
	refreshDisplay: function(startFresh)
	{
		this.ignoreEvents = true;
		
		if (startFresh)
		{
			this.vectorLayer.removeAllFeatures();
			for (var i in this.geoFeatures)
				this.geoFeatures[i].olMapFeature = null;
		}
		
		// make sure all waypoints features are on the layer, 
		// at the correct coordinates,
		for (var i in this.geoFeatures)
		{
			var gf = this.geoFeatures[i];
			this.refreshGeoFeature(gf);
		}
		
		this.ignoreEvents = false;
	},

	refreshGeoFeature: function(gf)
	{
		if (gf.featureType == 'point')
		{
			// make sure we have the feature on screen
			var coords = new OpenLayers.LonLat(gf.geoLong, gf.geoLat);
			coords.transform(VolaxMapApp.Projections.degrees, VolaxMapApp.Projections.google);
			
			if (gf.olMapFeature == null)
			{
				var point = new OpenLayers.Geometry.Point(coords.lon, coords.lat);
				var attributes = { caption: gf.title, };
				
				// we need to clone the default style, because we use the "label" attribute.
				var styleClone = {};
				for (x in VolaxMapApp.MapStyles.pointStyle)
					styleClone[x] = VolaxMapApp.MapStyles.pointStyle[x];
				
				gf.olMapFeature = new OpenLayers.Feature.Vector(point, attributes, styleClone);
				this.vectorLayer.addFeatures([gf.olMapFeature]);
			}
			
			gf.olMapFeature.geometry.x = coords.lon;
			gf.olMapFeature.geometry.y = coords.lat;
			gf.olMapFeature.style.label = gf.title;
			this.vectorLayer.drawFeature(gf.olMapFeature);
		}
		else if ((gf.featureType == 'route' || gf.featureType == 'area') && gf.waypoints)
		{
			var linePoints = [];
			for (var i in gf.waypoints)
			{
				var wp = gf.waypoints[i];
				var coords = new OpenLayers.LonLat(wp.geoLong, wp.geoLat);
				coords.transform(VolaxMapApp.Projections.degrees, VolaxMapApp.Projections.google);
				var point = new OpenLayers.Geometry.Point(coords.lon, coords.lat);
				linePoints.push(point);
			}
			
			var style = null;
			var geom = null;
			
			if (gf.featureType == 'route') {
				style = VolaxMapApp.MapStyles.routeStyle;
				geom = new OpenLayers.Geometry.LineString(linePoints);
			}
			else if (gf.featureType == 'area') {
				style = VolaxMapApp.MapStyles.areaStyle;
				geom = new OpenLayers.Geometry.Polygon(linePoints);
			}
	
			gf.olMapFeature = new OpenLayers.Feature.Vector(geom, null, style);
			this.vectorLayer.addFeatures([gf.olMapFeature]);
			this.vectorLayer.drawFeature(gf.olMapFeature);
		}
	},
	
	applyMarkers: function(jsonMarkersArray)
	{
		this.ignoreEvents = true;
	
		// should present the markers, as they are loaded already
		
		this.ignoreEvents = false;
	},
	
	routeChangeHappened: function()
	{
		this.ignoreEvents = true;
		this.refreshDisplay();
		this.ignoreEvents = false;
	},
	
	routeBeforeRemoveWaypoint: function(waypoint)
	{
		// remove the feature from the olMap.
		if (waypoint.feature != null)
		{
			this.vectorLayer.removeFeatures([ waypoint.feature ]);
			waypoint.feature = null;
		}
		
		// remove the mid point as well.
		if (waypoint.midFeature != null)
		{
			this.vectorLayer.removeFeatures([ waypoint.midFeature ]);
			waypoint.midFeature = null;
		}
		
		// if removed last waypoint, remove previous one's midpoint
		if (this.route.waypoints.length > 1)
		{
			var index = this.route.waypoints.indexOf(waypoint);
			if (index == this.route.waypoints.length - 1)
			{
				var prevWaypoint = this.route.waypoints[(index - 1)];
				this.vectorLayer.removeFeatures([ prevWaypoint.midFeature ]);
				prevWaypoint.midFeature = null;
			}
		}
	},

	showHidePoisOnMap: function(newValue)
	{
		this.showPoisOnMap = newValue;
		// should refresh map somehow...
		// i should have this.pointsOfInterest, an array of items.
		this.refreshDisplay(true);
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};

/**
 * Constant: OpenLayers.Feature.Vector.style
 * OpenLayers features can have a number of style attributes. The 'default' 
 *     style will typically be used if no other style is specified. These
 *     styles correspond for the most part, to the styling properties defined
 *     by the SVG standard. 
 *     Information on fill properties: http://www.w3.org/TR/SVG/painting.html#FillProperties
 *     Information on stroke properties: http://www.w3.org/TR/SVG/painting.html#StrokeProperties
 *
 * Symbolizer properties:
 * fill - {Boolean} Set to false if no fill is desired.
 * fillColor - {String} Hex fill color.  Default is "#ee9900".
 * fillOpacity - {Number} Fill opacity (0-1).  Default is 0.4 
 * stroke - {Boolean} Set to false if no stroke is desired.
 * strokeColor - {String} Hex stroke color.  Default is "#ee9900".
 * strokeOpacity - {Number} Stroke opacity (0-1).  Default is 1.
 * strokeWidth - {Number} Pixel stroke width.  Default is 1.
 * strokeLinecap - {String} Stroke cap type.  Default is "round".  [butt | round | square]
 * strokeDashstyle - {String} Stroke dash style.  Default is "solid". [dot | dash | dashdot | longdash | longdashdot | solid]
 * graphic - {Boolean} Set to false if no graphic is desired.
 * pointRadius - {Number} Pixel point radius.  Default is 6.
 * pointerEvents - {String}  Default is "visiblePainted".
 * cursor - {String} Default is "".
 * externalGraphic - {String} Url to an external graphic that will be used for rendering points.
 * graphicWidth - {Number} Pixel width for sizing an external graphic.
 * graphicHeight - {Number} Pixel height for sizing an external graphic.
 * graphicOpacity - {Number} Opacity (0-1) for an external graphic.
 * graphicXOffset - {Number} Pixel offset along the positive x axis for displacing an external graphic.
 * graphicYOffset - {Number} Pixel offset along the positive y axis for displacing an external graphic.
 * rotation - {Number} For point symbolizers, this is the rotation of a graphic in the clockwise direction about its center point (or any point off center as specified by graphicXOffset and graphicYOffset).
 * graphicZIndex - {Number} The integer z-index value to use in rendering.
 * graphicName - {String} Named graphic to use when rendering points.  Supported values include "circle" (default),
 *     "square", "star", "x", "cross", "triangle".
 * graphicTitle - {String} Tooltip for an external graphic.
 * backgroundGraphic - {String} Url to a graphic to be used as the background under an externalGraphic.
 * backgroundGraphicZIndex - {Number} The integer z-index value to use in rendering the background graphic.
 * backgroundXOffset - {Number} The x offset (in pixels) for the background graphic.
 * backgroundYOffset - {Number} The y offset (in pixels) for the background graphic.
 * backgroundHeight - {Number} The height of the background graphic.  If not provided, the graphicHeight will be used.
 * backgroundWidth - {Number} The width of the background width.  If not provided, the graphicWidth will be used.
 * label - {String} The text for an optional label. For browsers that use the canvas renderer, this requires either
 *     fillText or mozDrawText to be available.
 * labelAlign - {String} Label alignment. This specifies the insertion point relative to the text. It is a string
 *     composed of two characters. The first character is for the horizontal alignment, the second for the vertical
 *     alignment. Valid values for horizontal alignment: "l"=left, "c"=center, "r"=right. Valid values for vertical
 *     alignment: "t"=top, "m"=middle, "b"=bottom. Example values: "lt", "cm", "rb". Default is "cm".
 * labelXOffset - {Number} Pixel offset along the positive x axis for displacing the label. Not supported by the canvas renderer.
 * labelYOffset - {Number} Pixel offset along the positive y axis for displacing the label. Not supported by the canvas renderer.
 * labelSelect - {Boolean} If set to true, labels will be selectable using SelectFeature or similar controls.
 *     Default is false.
 * labelOutlineColor - {String} The color of the label outline. Default is 'white'. Only supported by the canvas & SVG renderers.
 * labelOutlineWidth - {Number} The width of the label outline. Default is 3, set to 0 or null to disable. Only supported by the canvas & SVG renderers.
 * fontColor - {String} The font color for the label, to be provided like CSS.
 * fontOpacity - {Number} Opacity (0-1) for the label
 * fontFamily - {String} The font family for the label, to be provided like in CSS.
 * fontSize - {String} The font size for the label, to be provided like in CSS.
 * fontStyle - {String} The font style for the label, to be provided like in CSS.
 * fontWeight - {String} The font weight for the label, to be provided like in CSS.
 * display - {String} Symbolizers will have no effect if display is set to "none".  All other values have no effect.
 */ 

VolaxMapApp.MapStyles =
{
	pointStyle: {
		strokeColor: '#cc0000',
		strokeOpacity: 1,
		strokeWidth: 3,
		fillColor: '#000000',
		fillOpacity: 0,
		pointRadius: 6,
		graphicZIndex: 15,
		graphicName: 'circle',
		
		// and the name
		//label: "${caption}",
		fontColor: '#444444',
		fontSize: '10px',
		fontFamily: 'Tahoma,Arial,Helvetiva,sans-serif',
		fontWeight: 'bold',
		labelAlign: 'center',
		labelXOffset: 0,
		labelYOffset: -15,
		labelOutlineColor: '#ffffff',
		labelOutlineWidth: 2,
	},
	routeStyle: {
		strokeDashstyle: 'solid', // [dot | dash | dashdot | longdash | longdashdot | solid]
		strokeColor: '#009900',
		strokeOpacity: 1,
		strokeWidth: 3,
		graphicZIndex: 3,
	},
	areaStyle: {
		strokeDashstyle: 'solid', // [dot | dash | dashdot | longdash | longdashdot | solid]
		strokeColor: '#000099',
		strokeOpacity: 1,
		strokeWidth: 3,
		graphicZIndex: 3,
	},
};
