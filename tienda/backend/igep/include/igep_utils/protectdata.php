<?php
if ($_POST['texto'])
	$codif = md5('salto-pre'.$_POST['texto'].'salto-post');
else
	$codif='';
?>
<html>
<head>
<meta http-equiv="Content-Type"
          content="text/html;charset=iso-8859-1" />
<title>Formulario para ocultar contraseñas en el codigo fuente</title>
    		<!--  meta http-equiv="Content-Type"
          content="text/html;charset=utf-8" /-->
</head>

<body bgcolor="#eeeeee">

</p>
<p align="center"><b>Formulario para ocultar información en el código fuente con md5 (1 sentido)</b></p>

<form action="" method="post">
  <center><table bgcolor="#cccccc" border="0" cellpadding="6"
  cellspacing="0" width="600">
    <tr>
      <td align="right" valign="top"><strong>Texto</strong></td>
      <td><input type="text" size="30" maxlength="30" name="texto"> Ej. abc</td>
    </tr>
    <tr>
      <td align="right" valign="top"><strong>Hash:</strong></td>
      <td><?php echo $codif; ?></td>
    </tr>

    <tr>
      <!-- td align="right" valign="top"><strong>Enviar</strong>&nbsp; <strong>&gt;</strong> </td -->
      <td align="center">&nbsp; 
      <input type="submit" name="Enviar" value=" Enviar ">
      <input type="reset" value="Borrar" name="B1"> </td>
    </tr>
  </table>
  </center>
</form>
</body>
</html>