<?php /* Smarty version 2.6.14, created on 2016-05-12 15:41:05
         compiled from igep_ordenarTabla.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<meta http-equiv='Cache-Control' content='no-cache' />
<title>ordenarTabla</title>
<?php echo '
<script  type=\'text/javascript\'>
//<![CDATA[
function actualizar() 
{	
	loc = parent.location;
    locationOrderLis = loc + "&panel=listar";
    this.parent.location = locationOrderLis;
	open(\'igep/blanco.htm\',\'oculto\');
}
//]]
</script>
'; ?>

</head>
<body onLoad="actualizar();">
</body>
</html>