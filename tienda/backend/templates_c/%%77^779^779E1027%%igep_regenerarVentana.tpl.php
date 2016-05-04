<?php /* Smarty version 2.6.14, created on 2016-04-06 16:23:50
         compiled from igep_regenerarVentana.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<title></title>
<?php echo '
<script type=\'text/javascript\' src=\'igep/js/window.js\'></script>
<script type=\'text/javascript\' src=\'igep/js/escape.js\'></script>
<script  type=\'text/javascript\'>
//<![CDATA[

';  echo $this->_tpl_vars['smty_loadScript'];  echo '

function regenerarVentana()
{
	urlRecarga = \'';  echo $this->_tpl_vars['smty_path'];  echo '\';
	if(urlRecarga!=\'\')
		parent.location.href = urlRecarga + \'';  echo $this->_tpl_vars['smty_parametrosGet'];  echo '\';		
	else{
		';  echo $this->_tpl_vars['smty_mensajeJS'];  echo ';
	}
}
//]]
</script>
'; ?>

</head>
<body onLoad="regenerarVentana()">
</body>
</html>