<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<title>salto modal</title>
{literal}
<script type='text/javascript' src='igep/js/ventanaModal.js'></script>
<script  type='text/javascript'>
//<![CDATA[


function actualizar() 
{
	{/literal}{$smty_paramsSaltoModal}{literal};
}

//]]
</script>
{/literal}
</head>
<body onLoad="actualizar();this.parent.document.forms[0].target='_self'">
</body>
</html>