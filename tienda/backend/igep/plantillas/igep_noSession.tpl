<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
{literal}
<script type='text/javascript' src='igep/js/window.js'></script>
<script type='text/javascript' src='igep/js/escape.js'></script>
<script  type='text/javascript'>
//<![CDATA[
function regenerarVentana()
{
	urlRecarga = '{/literal}{$smty_path}{literal}';
	if(urlRecarga!='')
		parent.location.href = urlRecarga;		
	else{
		alert('Entro mal');
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