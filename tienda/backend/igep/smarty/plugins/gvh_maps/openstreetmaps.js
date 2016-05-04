var lon = 0;
var lat = 0;
var zoomMod = 0;
var lonTrans;
var latTrans;

function reFresh(lonTrans, latTrans ,campoLongitud,campoLatitud,campoProyeccion) {

	//realizamos la transformación en el caso que se pase como parámetro una proyeccion diferente a la predeterminada

	lonlat = new OpenLayers.LonLat(lonTrans, latTrans);
	lonlat.transform(
	     new OpenLayers.Projection("EPSG:4326"),
	     new OpenLayers.Projection(campoProyeccion)
	);

	parent.document.getElementById(campoLongitud).value = lonlat.lat;	
	parent.document.getElementById(campoLatitud).value = lonlat.lon;	
	
	
}
	

function daCoordenadas(lon, lat,campoLongitud,campoLatitud, campoProyeccion)

{
	if(campoProyeccion!='EPSG:4326')
	{
		
		lonTrans = lon;
		latTrans = lat;
	
		setTimeout("reFresh(lonTrans, latTrans ,campoLongitud,campoLatitud,campoProyeccion)",1000);
		
	}
	
	else 
	{
		parent.document.getElementById(campoLongitud).value = lon;	
		parent.document.getElementById(campoLatitud).value = lat;
	}
}


function visor(campoLongitud, campoLatitud, zoom, proyeccion) {
	
	
	$( document ).ready(function() {
		 
		
        lon = document.getElementById(campoLongitud).value;
        lat = document.getElementById(campoLatitud).value;
                
        if (lat.length == 0 || lon.length == 0) {
        	lat = 39.300299186;
        	lon = -0.708618164;  
         }
        

        proyeccionMod = proyeccion;
        zoomMod = zoom; 
              
        var iframestr = "<div id='dialogomapa' style='position: relative; left: -6px; top: 7px;' width='845px'><iframe src='igep/smarty/plugins/gvh_maps/openstreetmaps.php?x="+lon+"&y="+lat+"&zoomlevel="+zoomMod+"&campoLongitud="+campoLongitud+"&campoLatitud="+campoLatitud+"&campoProyeccion="+proyeccionMod+"' style='border: solid #aaaaaa 1px;width:840px;height:540px'></iframe></div>";
          
		var myPos = {
                    my: "center center",
                    of: window,
                    collision: "fit"
                }
	
		
		$(iframestr).dialog(
				
			{
			   
				height: 600,
				width: 855,
				modal: true,
				position: myPos,
                autoOpen: false,
				resizable: false,
				close: function(event, ui)
				{
					  
                    $(this).dialog('destroy').remove();
                
                    // $("input[name*='"+campoLongitud+"']").focus().val(lon);
                    // $("input[name*='"+campoLatitud+"']").focus().val(lat);		
                   
				},
                    open: function (event, ui) {
				    $(this).css('overflow', 'hidden'); 
                                }				
			}
		);
		
		$('#dialogomapa').dialog('open');	
		 
	});
	
}