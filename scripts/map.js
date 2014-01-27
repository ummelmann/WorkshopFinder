var map, popup;
var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png',
cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttribution});	

var mapquestUrl = 'http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
	mapquestAttribution = "Data CC-By-SA by <a href='http://openstreetmap.org/' target='_blank'>OpenStreetMap</a>, Tiles Group05",
	mapquest = new L.TileLayer(mapquestUrl, {maxZoom: 18, attribution: mapquestAttribution, subdomains: ['1','2','3','4']});
		
var nexrad = new L.TileLayer.WMS("http://giv-siidemo.uni-muenster.de:8080/geoserver/wms", {
	layers: 'group05ws:workshops',
	format: 'image/png',
	transparent: true,
});
var heatmap = new L.TileLayer.WMS("http://giv-siidemo.uni-muenster.de:8080/geoserver/wms", {
	layers: 'group05ws:workshops',
	format: 'image/png',
	transparent: true,
  styles: 'group05ws:heatmap'
});
			
map = new L.Map('map', {
	center: new L.LatLng(51.956297, 7.633952), 
	zoom: 7,
	layers: [mapquest, nexrad,heatmap],
	zoomControl: true
});
		
map.addEventListener('click', onMapClick);
	popup = new L.Popup({
	maxWidth: 400
});
				

		function onMapClick(e) {
		    var latlngStr = '(' + e.latlng.lat.toFixed(3) + ', ' + e.latlng.lng.toFixed(3) + ')';
		    var BBOX = map.getBounds().toBBoxString();
		    var WIDTH = map.getSize().x;
		    var HEIGHT = map.getSize().y;
		    var X = map.layerPointToContainerPoint(e.layerPoint).x;
		    var Y = map.layerPointToContainerPoint(e.layerPoint).y;
        var URL = '?service=WFS&version=1.1.0&request=GetFeature&typeName=group05ws:workshops&outputFormat=json&BBOX='+BBOX+'&FEATURE_COUNT=5&HEIGHT='+HEIGHT+'&WIDTH='+WIDTH+'&FORMAT=image%2Fpng&INFO_FORMAT=text%2Fhtml&SRS=EPSG%3A4326&X='+X+'&Y='+Y;
		    $.get("http://giv-siidemo.uni-muenster.de:8080/geoserver/ows" + URL, function(data) {
                    feature = data.features[0]
		                popup.setContent(feature.name);
		                popup.setLatLng(e.latlng);
		                map.openPopup(popup);
		        }
		    );


		}
		
		function DoTheCheck() {
			if (document.checkform.getfeatureinfo.checked == true)
			  {map.addEventListener('click', onMapClick);}
			if (document.checkform.getfeatureinfo.checked == false)
			  {map.removeEventListener('click', onMapClick); map.closePopup(popup);}
		}
function GetLocation(location) {
  $("#new-workshop #lat").val(location.coords.latitude);
  $("#new-workshop #lon").val(location.coords.longitude);
  $("#new-workshop #acc").val(location.coords.accuracy);
  }
$(function() {
  navigator.geolocation.getCurrentPosition(GetLocation);
});
