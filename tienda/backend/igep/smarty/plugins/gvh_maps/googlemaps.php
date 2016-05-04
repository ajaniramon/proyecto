<html>
  <head>
    <title>Accessing arguments in UI events</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
function initialize() {
<?php 
//Si no introduce parametros centramos en la comunidad.
if (empty($_GET['y']) OR empty($_GET['x'])) {
  echo 'var mapOptions = {
    zoom: 7,
    center: new google.maps.LatLng(39.300299186,-0.708618164),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };';
  echo 'var map = new google.maps.Map(document.getElementById(\'map-canvas\'), mapOptions);';
  echo 'marker = new google.maps.Marker () ;';  
  echo 'marker.setMap(map);';
}
else {
	
  //Centramos en el punto indicado y mostramos la marca
  echo 'var mapOptions = {';
  echo 'zoom: '.$_GET['zoomlevel'].',';
  echo 'center: new google.maps.LatLng('.$_GET['y'].','.$_GET['x'].'),';
  echo 'mapTypeId: google.maps.MapTypeId.ROADMAP';
  echo '};';
  echo 'var map = new google.maps.Map(document.getElementById(\'map-canvas\'), mapOptions);';
  echo 'marker = new google.maps.Marker ({position: new google.maps.LatLng('.$_GET['y'].','.$_GET['x'].'), title: "PUNTO"}) ;';  
  echo 'marker.setMap(map);';
}
?>
  
  
  google.maps.event.addListener(map, 'click', function(e) {
    placeMarker(e.latLng, map, marker);
    //Fijamos los valores a las variables.
    //Provisionalmente, fijamos la precisión a 9
    //parent.document.window
    parent.lng = e.latLng.lng().toFixed(9);
    parent.lat = e.latLng.lat().toFixed(9);
  });
}

function placeMarker(position, map, marker) {
  marker.setPosition(position);
  map.panTo(position);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
