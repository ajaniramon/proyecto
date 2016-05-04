var lng=0;
var lat=0;

function visor(campoLongitud, campoLatitud, zoom) {

	$( document ).ready(function() {
                lng = document.getElementById(campoLongitud).value;
                lat = document.getElementById(campoLatitud).value;
                
                if ((lng!=0)&&(lat!=0)) {
                    var iframestr = "<div id='dialogomapa' style='position: relative; left: -6px; top: 7px;' width='845px'><iframe src='"+url+"?x="+lng+"&y="+lat+"&zoomlevel="+zoom+"&plugins=MeasuresTool,TerrasitFrame,TerrasitSearch,CaptarGeometrias,BaseLayerSelector&srs=4326' style='border: solid #aaaaaa 1px;width:840px;height:540px'></iframe></div>";
		} else {
			var iframestr = "<div id='dialogomapa' style='position: relative; left: -6px; top: 7px; width:845px'><iframe src='http://localhost/ICVGeo/?plugins=MeasuresTool,TerrasitFrame,TerrasitSearch,CaptarGeometrias,BaseLayerSelector' style='border: solid #aaaaaa 1px;width:840px;height:540px'></iframe></div>";		
		}
		var olng = {value: lng, id: "lng"};
		var olat = {value: lat, id: "lat"};
		$olng = $(olng);
		$olat = $(olat);
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
                                        //document.getElementById(campoLongitud).value = $oyutm[0].value;
                                        //document.getElementById(campoLatitud).value = $oxutm[0].value;
                                        $("input[name*='"+campoLongitud+"']").focus().val($olng[0].value);
                                        $("input[name*='"+campoLatitud+"']").focus().val($olat[0].value);					
                                        
				},
                                open: function (event, ui) {
				    $(this).css('overflow', 'hidden'); 
                                }				
			}
		);
		$('#dialogomapa').dialog('open');
	});
}