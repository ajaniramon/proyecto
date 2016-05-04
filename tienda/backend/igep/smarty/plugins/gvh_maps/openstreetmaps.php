<html>
  <head>
    <title>Accessing arguments in UI events</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    
    <script src="../jquery/jquery-ui-1.10.3/OpenLayers.js"></script>
    
    <script src="../jquery/jquery-ui-1.10.3/PosicionarMapa.js"></script>
    
   	<script src="../jquery/jquery-ui-1.10.3/proj4js-compressed.js""></script>
     
    <script src="openstreetmaps.js"></script>

    <link rel='stylesheet' href='mapaStreetMaps.css' type='text/css'>
    
     <script>
     function espera(lon, lat ,campoLongitud,campoLatitud,campoProyeccion) {
		lonlat = new OpenLayers.LonLat(lat,lon);
       	lonlat.transform(
            new OpenLayers.Projection(campoProyeccion),
            new OpenLayers.Projection("EPSG:4326")
       	);

      	lon=lonlat.lon;
   		lat=lonlat.lat;

   		dibujarMapa('demoMap', lon, lat, zoomMod, campoLongitud,campoLatitud, campoProyeccion );
     }
    	function init(lon,lat,zoomMod) {
	
    		if(campoProyeccion!='EPSG:4326')
    		{	
        		var lonlat = new OpenLayers.LonLat(lat,lon);

         		 lonlat.transform(
               	new OpenLayers.Projection(campoProyeccion),
              	 new OpenLayers.Projection("EPSG:4326")
        		  );
         	 	
    			setTimeout("espera(lon, lat ,campoLongitud,campoLatitud,campoProyeccion)",1000);
    	      	
    		}
    		else
    		{
    	 		dibujarMapa('demoMap', lon, lat, zoomMod, campoLongitud,campoLatitud, campoProyeccion );
        	}
    	
		}
	
    </script>
   
  </head>
  
<body onload="javaScript:init(lon,lat,zoomMod)">


 <?php 
$lat = ($_GET['y']);
$lon = ($_GET['x']);
$zoomMod = ($_GET['zoomlevel']);
$campoLongitud = ($_GET['campoLongitud']);
$campoLatitud = ($_GET['campoLatitud']);
$campoProyeccion = ($_GET['campoProyeccion']);


?>

<script>
lat = "<?php echo $lat; ?>" ;
lon = "<?php echo $lon; ?>" ;
zoomMod = "<?php echo $zoomMod; ?>" ;
campoLongitud = "<?php echo $campoLongitud; ?>" ;
campoLatitud = "<?php echo $campoLatitud; ?>" ;
campoProyeccion = "<?php echo $campoProyeccion; ?>" ;

</script>
   <div id="demoMap">
   	<span  style="position: absolute; right: 18px; z-index: 1005;">Licencia: CC-BY-SA-2.0 copyright Contribuidores de OpenStreetMap</span>
   </div>
  </body>
</html>
