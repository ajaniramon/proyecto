<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<title></title>
{literal}
<script type='text/javascript' src='igep/js/window.js'></script>
<script type='text/javascript' src='igep/js/escape.js'></script>
<script  type='text/javascript'>
//<![CDATA[

{/literal}{$smty_loadScript}{literal}

function regenerarVentana()
{
	urlRecarga = '{/literal}{$smty_path}{literal}';
	if(urlRecarga!='')
		parent.location.href = urlRecarga + '{/literal}{$smty_parametrosGet}{literal}';		
	else{
		{/literal}{$smty_mensajeJS}{literal};
	}
}
//]]
</script>
{/literal}
</head>
<body onLoad="regenerarVentana()">
</body>
</html>