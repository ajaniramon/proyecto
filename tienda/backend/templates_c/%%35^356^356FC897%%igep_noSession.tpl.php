<?php /* Smarty version 2.6.14, created on 2016-05-12 13:11:46
         compiled from igep_noSession.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<?php echo '
<script type=\'text/javascript\' src=\'igep/js/window.js\'></script>
<script type=\'text/javascript\' src=\'igep/js/escape.js\'></script>
<script  type=\'text/javascript\'>
//<![CDATA[
function regenerarVentana()
{
	urlRecarga = \'';  echo $this->_tpl_vars['smty_path'];  echo '\';
	if(urlRecarga!=\'\')
		parent.location.href = urlRecarga;		
	else{
		alert(\'Entro mal\');
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