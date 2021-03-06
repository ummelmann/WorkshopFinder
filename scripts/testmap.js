var map, popup;
var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png',
cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttribution});	

var mapquestUrl = 'http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
	mapquestAttribution = "Data CC-By-SA by <a href='http://openstreetmap.org/' target='_blank'>OpenStreetMap</a>, Tiles  <a href='http://open.mapquest.com' target='_blank'>MapQuest</a>",
	mapquest = new L.TileLayer(mapquestUrl, {maxZoom: 18, attribution: mapquestAttribution, subdomains: ['1','2','3','4']});
		
var nexrad = new L.TileLayer.WMS("http://suite.opengeo.org/geoserver/usa/wms", {
	layers: 'usa:states',
	format: 'image/png',
	transparent: true
	});
			
		map = new L.Map('map', {
			center: new L.LatLng(44.095475729465, -72.388916015626), 
			zoom: 7,
			layers: [mapquest, nexrad],
			zoomControl: true
		});
		
		map.addEventListener('click', onMapClick);
		
		popup = new L.Popup({
			maxWidth: 400
		});
				
		/*function onMapClick(e) {
			var latlngStr = '(' + e.latlng.lat.toFixed(3) + ', ' + e.latlng.lng.toFixed(3) + ')';
			var BBOX = map.getBounds()._southWest.lng+","+map.getBounds()._southWest.lat+","+map.getBounds()._northEast.lng+","+map.getBounds()._northEast.lat;
			var WIDTH = map.getSize().x;
			var HEIGHT = map.getSize().y;
			var X = map.layerPointToContainerPoint(e.layerPoint).x;
			var Y = map.layerPointToContainerPoint(e.layerPoint).y;
			var URL = 'http://suite.opengeo.org/geoserver/usa/wms?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=usa:states&QUERY_LAYERS=usa:states&STYLES=&BBOX='+BBOX+'&FEATURE_COUNT=5&HEIGHT='+HEIGHT+'&WIDTH='+WIDTH+'&FORMAT=image%2Fpng&INFO_FORMAT=text%2Fhtml&SRS=EPSG%3A4326&X='+X+'&Y='+Y;
			//alert(URL);
			popup.setLatLng(e.latlng);
			popup.setContent("<iframe src='"+URL+"' width='300' height='100' frameborder='0'><p>Your browser does not support iframes.</p></iframe>");
			map.openPopup(popup);
	}*/

		function onMapClick(e) {
		    var latlngStr = '(' + e.latlng.lat.toFixed(3) + ', ' + e.latlng.lng.toFixed(3) + ')';
		    var BBOX = map.getBounds().toBBoxString();
		    var WIDTH = map.getSize().x;
		    var HEIGHT = map.getSize().y;
		    var X = map.layerPointToContainerPoint(e.layerPoint).x;
		    var Y = map.layerPointToContainerPoint(e.layerPoint).y;
		    var URL = '?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=usa:states&QUERY_LAYERS=usa:states&STYLES=&BBOX='+BBOX+'&FEATURE_COUNT=5&HEIGHT='+HEIGHT+'&WIDTH='+WIDTH+'&FORMAT=image%2Fpng&INFO_FORMAT=text%2Fhtml&SRS=EPSG%3A4326&X='+X+'&Y='+Y;
		    URL = escape(URL);
		    $.ajax({
		        url: "wms_proxy.php?&args=" + URL,
		        dataType: "html",
		        type: "GET",
		        //async: false,
		        success: function(data) {
		            if (data.indexOf("<table") != -1) {
		                popup.setContent(data);
		                popup.setLatLng(e.latlng);
		                map.openPopup(popup);

		                // dork with the default return table - get rid of geoserver fid column, apply bootstrap table styling
		                /*if ($(".featureInfo th:nth-child(1)").text() == "fid") $('.featureInfo td:nth-child(1), .featureInfo th:nth-child(1)').hide();
		                $("caption.featureInfo").removeClass("featureInfo");
		                $("table.featureInfo").addClass("table").addClass("table-striped").addClass("table-condensed").addClass("table-hover").removeClass("featureInfo");*/
		            }
		        }
		    });


		}
		
		function DoTheCheck() {
			if (document.checkform.getfeatureinfo.checked == true)
			  {map.addEventListener('click', onMapClick);}
			if (document.checkform.getfeatureinfo.checked == false)
			  {map.removeEventListener('click', onMapClick); map.closePopup(popup);}
		}
