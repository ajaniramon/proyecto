<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<meta http-equiv='Cache-Control' content='no-cache' />
<title>ordenarTabla</title>
{literal}
<script  type='text/javascript'>
//<![CDATA[
function actualizar()
{
	loc = parent.location+"";
    locationOrderLis = loc.replace(/#/g,""); //+ "&panel=listar";
    this.parent.location = locationOrderLis;
	open('igep/blanco.htm','oculto');
}
//]]
</script>
{/literal}
</head>
<body onLoad="actualizar();">
</body>
</html>