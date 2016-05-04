var lng=0;
var lat=0;

function visor(campoLongitud, campoLatitud, zoom) {

	$( document ).ready(function() {
                lng = document.getElementById(campoLongitud).value;
                lat = document.getElementById(campoLatitud).value;
                var iframestr = "<div id='dialogomapa' style='position: relative; left: -6px; top: 7px;' width='845px'><iframe src='igep/smarty/plugins/gvh_maps/googlemaps.php?x="+lng+"&y="+lat+"&zoomlevel="+zoom+"' style='border: solid #aaaaaa 1px;width:840px;height:540px'></iframe></div>";
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
                                    $("input[name*='"+campoLongitud+"']").focus().val(lng);
                                    $("input[name*='"+campoLatitud+"']").focus().val(lat);					
				},
                                open: function (event, ui) {
				    $(this).css('overflow', 'hidden'); 
                                }				
			}
		);
		$('#dialogomapa').dialog('open');
	});
}