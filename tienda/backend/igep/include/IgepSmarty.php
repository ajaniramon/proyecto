<?php
/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
/**
 * Created on 21-mar-2005
 * Clase de apoyo a Smarty, sustituye parte de la funcionalidad de la clase
 * Componentes_web, cada plugin creará una instancia de esta clase que
 * para poder manejar el javascript asociado a la instancia del plugin en
 * función de sus parámetros.
 * 
 *
 * @version	$Id: IgepSmarty.php,v 1.86 2011-05-09 09:09:11 afelixf Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 **/
 
 
class IgepSmarty
{
	var $preScript;
	var $v_eventosPesos;
	var $v_eventos;
	var $postScript;
	var $scriptLoadIgep;
	var $scriptLoadUsuario;	

	function __construct()
	{
		$this->IgepSmarty();
	} //FIN constructor

	function IgepSmarty()
	{	
		$this->preScript='';	
		$this->postScript='';
		$this->v_eventos = array();
		$this->v_eventosPesos = array();
		
		$configuration = ConfigFramework::getConfig();
		$customDirname = $configuration->getCustomDirName();
		define("APP_PATH",''); 
		define("IMG_PATH_CUSTOM",APP_PATH.'custom/'.$customDirname.'/images/');
	} //FIN constructor

	function inicioVentana()
	{
		$cadenaJs = "var documento = document; //Documento será siempre una referencia al document 'visible'\n";
		$cadenaJs .= "if (document.getElementById('oculto') == null) documento = parent.document; \n";
		$this->preScript .= $cadenaJs;
	} //FIN addPreScript

	
	function addPreScript($script)
	{
		$this->preScript .= $script."\n";
	} //FIN addPreScript


	function getPreScript($conCabecera=true)
	{
		$retorno ='';
		if ($this->preScript!='')
		{
			if ($conCabecera)
			{
				$retorno.="<script  type='text/javascript'>\n";
				$retorno.="<!--//--><![CDATA[//><!--\n";
				$retorno.=$this->preScript;
				$retorno.="\n//--><!]]>\n</script>\n";
			}
			else
			{
				$retorno.= $this->preScript;
			}
		}
		return ($retorno);
	} //FIN getPreScript


	function addPostScript($script)
	{
		$this->postScript .= $script."\n";
	} //FIN addPostScript
	

	function getPostScript($conCabecera=true)
	{			
		$retorno ='';
		if ($this->postScript!='')
		{	
			if ($conCabecera)
			{
				$retorno.="<script  type='text/javascript'>\n";
				$retorno.="<!--//--><![CDATA[//><!--\n";
				$retorno.=$this->postScript;
				$retorno.="\n//--><!]]>\n";
				$retorno.="</script>\n"; 
		
			}
			else
			{
				$retorno.=$this->postScript;
			}
		}
		return($retorno);
	} //FIN getPostScript

	
	/**
	 * Esta función se encarga de almacenar el script que se ejecutará en el evento onLoad.
	 * @param string	$script	String el codigo que se quiere añadir al evento OnLoad
	 * @param string	$escIni	Indica el tipo de Script. Por definición de IGEP tenemos dos tipos: IGEP y USUARIO. 
	 * El primero corresponde con los scripts puntuales que IGEP tiene que inyectar en la ventana. El segundo 
	 * responde a los scripts que el usuario quiere que se carguen en la ventana durante toda su ejecución. 
	 * @access public
	 */
	
	function addScriptLoad($script, $tipo='IGEP')
	{
		//$script = 'alert(document.lis_paginacion.nom_variable)'.$script;
		
		if($tipo=='USUARIO')			
			$this->scriptLoadUsuario.=$script;
		else
			$this->scriptLoadIgep.=$script;
	}//Fin addScriptLoad

	
	function getScriptLoad($conCabecera=true)
	{
		$script='';
		
		if (($this->scriptLoadUsuario!='')or($this->scriptLoadIgep!=''))
		{	
			if ($conCabecera)
			{
				$script .="<script type='text/javascript'>\n";
				$script .="<!--//--><![CDATA[//><!--\n";
				$script .=$this->scriptLoadIgep."\n";
				$script .=$this->scriptLoadUsuario."\n";
				$script .="//--><!]]>\n";
				$script .="</script>\n";
			}		
			else		
				$script .= $this->scriptLoadIgep."\n".';'.$this->scriptLoadUsuario;
			$this->scriptLoadIgep = '';
			return $script;		
		}
		else
			return '';	
	}//Fin de getScriptLoad


	/**
	 * _validaEvento: Esta función se asegura de que el evento Javascript exista y este correctamente escrito 
	 * @param string	$evento	Cadena de texto con el nombre del evento
	 * @access private
	 */
	function _validaEvento(&$evento)
	{		
		$v_eventos = array (
			'onfocus' => 'onFocus',
			'onblur' => 'onBlur',
			'onchange' => 'onChange',
			'onabort' => 'onAbort',
			'onclick' => 'onClick',
			'ondblclick' => 'onDblClick',
			'ondragdrop' => 'onDragDrop',
			'onerror' => 'onError',
			'onfocus' => 'onFocus',
			'onkeydown' => 'onKeyDown',
			'onkeypress' => 'onKeyPress',
			'onkeyup' => 'onKeyUp',
			'onload' => 'onLoad',
			'onmousedown' => 'onMouseDown',
			'onmousemove' => 'onMouseMove',
			'onmouseout' => 'onMouseOut',
			'onmouseover' => 'onMouseOver',
			'onmouseup' => 'onMouseUp',
			'onmove' => 'onMove',
			'onresize' => 'onResize',
			'onreset' => 'onReset',
			'onselect' => 'onSelect',
			'onsubmit' => 'onSubmit',
			'onunload' => 'onUnLoad'
		);
		if (array_key_exists(trim(strtolower($evento)), $v_eventos))
		{
			$evento = $v_eventos[trim(strtolower($evento))];
			return true;
		}
		else
		{
			$evento ='EVENTOINEXISTENTE';
			return false;
		}
	}


	/**
	 * addAccionEvento: Esta función registra una llamada a una función
	 * javaScript en un evento determinado, opcionalmente puede introducirse
	 * "importancia" o peso que servira para ordenar las distintas invocaciones
	 * asociadas a un mismo evento 
	 * @param string	$evento	Cadena de texto con el nombre del Evento
	 * @param string	$script	Cadena de texto con el nombre del Evento
	 * @param int		$pesoAccion	Importancia o peso del script. Ordena los scrips asociados a un evento
	 * @access public
	 */
	function addAccionEvento($evento, $script, $pesoAccion=-1)
	{	
		if (!$this->_validaEvento($evento)) die ('Error JAVASCRIPT, evento '.$evento.' inexistente.');	
	
		if ($pesoAccion== -1)
		{
			if (!is_array($this->v_eventos[$evento]))
			{	
				$this->v_eventos[$evento] = array();			
			}
			array_push($this->v_eventos[$evento], $script);			
		}
		else
		{
			while (isset($this->v_eventosPesos[$evento][$pesoAccion]))
			{
				++$pesoAccion;				
			}			
			$this->v_eventosPesos[$evento][$pesoAccion] = $script;				
		}
	} //FIN addAccionEvento

	
	function _getAccion($nombreEvento, $v_Scripts)
	{	
		$script=' ';	
		if (is_array($v_Scripts) )
		{				
			foreach($v_Scripts as $accion)	
			{			
				$script.= $accion.';';
			}		
			//Añadimos la comprobacion de estado, si soy READONLY no disparo acciones:
			$iniScriptEstado = "if (this.readOnly != true) {";
			$script = $nombreEvento.'="javascript:'.$iniScriptEstado.$script.'};" ';
		}
		return($script);		
	} //FIN _getAccion

	
	function getAcciones()
	{	
		$script = ' ';
		$v_eventosFinal = array();
			
		foreach($this->v_eventosPesos as $evento=>$vScriptPesos)
		{
			krsort($vScriptPesos);
			$v_eventosFinal[$evento] = $vScriptPesos; 
		}
		foreach($this->v_eventos as $evento=>$valor)
		{
			foreach($valor as $scriptEjecutable){
				if(empty($v_eventosFinal[$evento]))
					$v_eventosFinal[$evento] = array();
				array_push($v_eventosFinal[$evento], $scriptEjecutable);
			}
		}
		$numEventos = count($v_eventosFinal);
		if ($numEventos>0) $v_nombreEvento = array_keys($v_eventosFinal);		
		for($i=0; $i<$numEventos; $i++)
		{	
			$nombreEvento = $v_nombreEvento[$i];
			$script.=$this->_getAccion($nombreEvento,$v_eventosFinal[$nombreEvento]);
		}
			
		return($script);		
	} //FIN getAcciones


	
	/**
	 * escapeIgep: Funcion para sustituir carácteres especiales.
	 * La funcion sustituye los carácteres problemáticos por una cadena
	 * formada por un prefijo ($escIni), la raiz (letra de representación
	 * del carácter) y un sufijo ($escFin)
	 * Los carácteres a sustituir son:
	 * \b	Backspace			raíz: b
	 * \f	Form feed			raíz: f
	 * \r	Retorno de carro	raíz: r
	 * \n	Linea Nueva			raíz: n
	 * \t	Tabulador			raíz: t
	 * \'	Comilla simple		raíz: cs
	 * \"	Comilla doble		raíz: cd
	 * \\	Contrabarra			raíz: cb
	 * 
	 * La funcion antagónica es desescapeIGEP.
	 * Existen funciones similares en javascript para poder enviar
	 * o recibir cadenas problemáticas en entre los dos lenguajes
	 * @access public
	 * @param string	$cadena	String donde se realiza el reemplazo
	 * @param string	$escIni	Prefijo de sustitución para el caracter
	 * @param string	$escFin	Sufijo de sustitución para el caracter
	 */
	 
	static function escapeIGEP(&$cadena, $escIni="!_", $escFin="_!")
	{		
		$cadena = str_replace("\b", $escIni.'b'.$escFin, $cadena);
		$cadena = str_replace("\f", $escIni.'f'.$escFin, $cadena);
		$cadena = str_replace("\r", $escIni.'r'.$escFin, $cadena);
		$cadena = str_replace("\n", $escIni.'n'.$escFin, $cadena);
		$cadena = str_replace('\t', $escIni.'t'.$escFin, $cadena);
		$cadena = str_replace("'", $escIni.'cs'.$escFin, $cadena);
		$cadena = str_replace("\"", $escIni.'cd'.$escFin, $cadena);
		$cadena = str_replace("\\", $escIni.'cb'.$escFin, $cadena);	
		
		return($cadena);
	} //FIN escapeIGEP


	
	/**
	 * desescapeIgep: Funcion para restablecer los carácteres especiales.
	 * La funcion restablece los carácteres problemáticos de una cadena donde
	 * se ha llevado acabo una sustituyción especial de escapeIgep()
	 * Los carácteres remplazados son:
	 * \b	Backspace			raíz: b
	 * \f	Form feed			raíz: f
	 * \r	Retorno de carro	raíz: r
	 * \n	Linea Nueva			raíz: n
	 * \t	Tabulador			raíz: t
	 * \'	Comilla simple		raíz: cs
	 * \"	Comilla doble		raíz: cd
	 * \\	Contrabarra			raíz: cb
	 * 
	 * La funcion antagónica es escapeIGEP.
	 * Existen funciones similares en javascript para poder enviar
	 * o recibir cadenas problemáticas en entre los dos lenguajes
	 * 
	 * @access public
	 * @param string	$cadena	String donde se realiza el reemplazo
	 * @param string	$escIni	Prefijo de sustitución para el caracter
	 * @param string	$escFin	Sufijo de sustitución para el caracter
	 */
	function desescapeIGEP(&$cadena, $escIni="!_", $escFin="_!")
	{	
		$cadena = str_replace($escIni.'b'.$escFin, "\b", $cadena);
		$cadena = str_replace($escIni.'f'.$escFin, "\f", $cadena);
		$cadena = str_replace($escIni.'r'.$escFin, "\r", $cadena);
		$cadena = str_replace($escIni.'n'.$escFin, "\n", $cadena);
		$cadena = str_replace($escIni.'t'.$escFin, "\t", $cadena);
		$cadena = str_replace($escIni.'cs'.$escFin, "'", $cadena);
		$cadena = str_replace($escIni.'cd'.$escFin, "\"", $cadena);
		$cadena = str_replace($escIni.'cb'.$escFin, "\\", $cadena);
		return($cadena);
	} //FIN escapeIGEP


	//A partir de un resultado genera el JavaScript necesario para que la lista se recargue por el oculto
	static function getJsLista($campoDestinoJs, $resultado){
		$opciones = '';
		//Si no tiene resultado hay que indicar que el seleccionado de la lista es uno vacio
		if(count($resultado['lista'])==0)		
			$opciones.="insertar_opcion(\"\",\"\",1);\n";
		else 
		{
			foreach($resultado['lista'] as $fila)
			{
				if($resultado['seleccionado']==$fila['valor'])					
					$opciones.="insertar_opcion(\"".$fila["valor"]."\",desescapeIGEPjs('".IgepSmarty::escapeIGEP($fila["descripcion"])."'),1);\n";
				else
					$opciones.="insertar_opcion(\"".$fila["valor"]."\",desescapeIGEPjs('".IgepSmarty::escapeIGEP($fila["descripcion"])."'),0);\n";				 
			}
		}
		$opciones.= "cambia(formulario,'".$campoDestinoJs."',opciones);\n";
		$opciones .= "opciones.length = 0;\n";
		
		//Insertamos este código para que se copie el valor en el lins o lcam
		// Solo en el caso de que no estemos en un panel de búsqueda pq no existe valor anterior
		$actualizarCampoOculto = "if (formulario != 'F_fil') {";
		$actualizarCampoOculto.= "eval('parent.document.forms[\"'+formulario+'\"].";
		$actualizarCampoOculto.= 'l'.$campoDestinoJs.".value=desescapeIGEPjs(\"";
		$actualizarCampoOculto.= IgepSmarty::escapeIGEP($resultado['seleccionado'])."\")');\n";
		$actualizarCampoOculto.= "};";
		
		$opciones.= $actualizarCampoOculto;
		return $opciones;
	}//Fin de getJsLista



	/**
	 * igepSmarty::getJsMensaje Genera un mensaje HTML/Js a partir de un objeto mensaje de 
	 * REVIEW Queda pendiente de discutir con el equipo de negocio si se deja aquí,
	 * o se lleva a otra clase intermedia (IgepPantalla... etc...)   
	 *
	 * @access	public
	 * @param	Object	$objMensaje	Objeto Mansaje de gvHidra
	 * @return	string	Cadena JavaScrip HTML correspondiente al mensaje 
	 */
	static function getJsMensaje(&$objMensaje)
	{			
		$cadenaJS ="";
		$cadenaJS .="aviso=parent.aviso;";
		$cadenaJS .="aviso.set('aviso', 'capaAviso'";
		$cadenaJS .=", '";		
		$cadenaJS .=$objMensaje->getTipo();
		$cadenaJS .="', '";
		$cadenaJS .=$objMensaje->getCodigo();
		$cadenaJS .="', desescapeIGEPjs('";
		$descripcion = $objMensaje->getDescripcionCorta();		
		$cadenaJS .=IgepSmarty::escapeIGEP($descripcion);
		$cadenaJS .="'), desescapeIGEPjs('";
		$descripcion = $objMensaje->getDescripcionLarga();	
		$cadenaJS .=IgepSmarty::escapeIGEP($descripcion);
		$cadenaJS .="')";		
		$cadenaJS .=");aviso.mostrarAviso();";
		return $cadenaJS; 		
	}

	static function getJsSetCampoTexto($campoDestino, $valor='')
	{
		// Comprobamos si el campo existe en el formulario
		$cadenaJs = "\n\n if (eval(parent.document.getElementById('".$campoDestino."'))) {\n";
			$cadenaJs .= " if (parent.document.getElementById('".$campoDestino."').tagName=='IMG') {\n";
				$cadenaJs .= "eval('parent.document.forms[\"'+formulario+'\"].";
				$cadenaJs .= $campoDestino.".src = desescapeIGEPjs(\"".IgepSmarty::escapeIGEP($valor)."\")');\n";
			$cadenaJs .= "}";
			$cadenaJs .= "else {";
				$cadenaJs .= "eval('parent.document.forms[\"'+formulario+'\"].";
				$cadenaJs .= $campoDestino.".value = desescapeIGEPjs(\"";
				$cadenaJs .= IgepSmarty::escapeIGEP($valor)."\")');\n";
			$cadenaJs .= "}";
		$cadenaJs .= "} \n";
		return $cadenaJs;
	}
	
	
	static function getJsSetSelected($nombreCampoDestino, $valor='')
	{					
		$cadenaJs = "if (document.getElementById('oculto') == null) // estoy en el oculto\n";
		$cadenaJs .= "miFormulario = eval('parent.document.forms[\"'+formulario+'\"]');\n";
		$cadenaJs .= "else miFormulario = eval('document.forms[\"'+formulario+'\"]');\n";
		$cadenaJs .= "miSelector = miFormulario.$nombreCampoDestino;\n";
		$cadenaJs .= "switch (miSelector.type)
					{
						case 'select-one':
							setSelectedOption(formulario,'$nombreCampoDestino', desescapeIGEPjs(\"".IgepSmarty::escapeIGEP($valor)."\"));
						break;";

		if (is_array($valor))
		{									
			$cadenaJs .= "case 'select-multiple':";
				$cadenaJs .= "valorMult = new Array();";
				$cadenaJs .= "valorMult=[";
					
				for($i=0;$i<count($valor);$i++)
				{
					$opc = IgepSmarty::escapeIGEP($valor[$i]);
					$cadenaJs .= "desescapeIGEPjs('".$opc."')";
					if ($i<(count($valor)-1))
					$cadenaJs .= ",";
				}
				$cadenaJs .= "];";
				$cadenaJs .= "setSelectedMultipleOption(formulario,'$nombreCampoDestino', valorMult);";
			$cadenaJs .= "break;";
		}
						
		$cadenaJs .= "	case 'radio':
							if (miSelector.length > 1)
								setSelectedRadio(formulario,'$nombreCampoDestino', desescapeIGEPjs(\"".IgepSmarty::escapeIGEP($valor)."\"));
						break;
					}";
		return $cadenaJs;
	}

	
	static function getJsSetVisible($campoDestino, $valor='')
	{
		//Inicializamos las variables
		$cadenaJs = "\n";
		//Referenciamos el campo si existe...
		$cadenaJs .= "var capaVi = documento.getElementById('";
		$cadenaJs .= 'IGEPVisible'.$campoDestino."');\n";
		$cadenaJs .= "if (capaVi) {\n";
		
		if($valor===false)
		{	
			$cadenaJs .="capaVi.style.visibility ='hidden';\n";
		}
		elseif ($valor===true)
		{			
			$cadenaJs .="capaVi.style.visibility ='visible';\n";
		}		
		
		// Hay que tratar el botón tooltip de la ventana de selección
		$cadenaJs .= "destino = '".$campoDestino."';";	
		$cadenaJs .= "vDestino = destino.split('___');";
		$cadenaJs .= "if (vDestino.length > 1)"; // No estamos en panel búsqueda	
			$cadenaJs .= "var capaViBtn = documento.getElementById('";
			$cadenaJs .= "IGEPVisibleBtn'+vDestino[1]+'___'+vDestino[2]);\n";
		$cadenaJs .= "else \n";	
			$cadenaJs .= "var capaViBtn = documento.getElementById('";
			$cadenaJs .= "IGEPVisibleBtn'+vDestino[0]);\n";			
		$cadenaJs .= "if (capaViBtn) \n";
		
		if($valor===false)
		{	
			$cadenaJs .="capaViBtn.style.visibility ='hidden';\n";
		}
		elseif ($valor===true)
		{			
			$cadenaJs .="capaViBtn.style.visibility ='visible';\n";
		}
		$cadenaJs .= "}\n";
		
// else dedicado a tratar los Checkbox		
		$cadenaJs .= "else { \n";
			$cadenaJs .= "nomCampo = '".$campoDestino."';";
			$cadenaJs .= "posicion = nomCampo.indexOf('fil',0);\n";
			$cadenaJs .= "if (posicion == 0) {\n";
				$cadenaJs .= "capa = 'IGEPVisiblechkbfil_".$campoDestino."';";
				$cadenaJs .= "var capaVi = documento.getElementById(capa);\n";
			$cadenaJs .= "}\n";
			$cadenaJs .= "else {\n";
			$cadenaJs .= "posicion = nomCampo.indexOf('cam___',0);\n";	
				$cadenaJs .= "if (posicion == 0) {\n";
					$cadenaJs .= "capa = 'IGEPVisiblec".$campoDestino."';";
					$cadenaJs .= "var capaVi = documento.getElementById(capa);\n";
				$cadenaJs .= "}\n";
			$cadenaJs .= "}\n";
			
			$cadenaJs .= "if (capaVi) \n";
			if($valor===false)
			{	
				$cadenaJs .="capaVi.style.visibility ='hidden';\n";
			}
			elseif ($valor===true)
			{			
				$cadenaJs .="capaVi.style.visibility ='visible';\n";
			}
		$cadenaJs .= "}\n";
		
		return $cadenaJs;
	}//Fin getJsSetVisible

	
	static function getJsSetOrder($campoDestino, $orden='', $tipoComp=null)
	{
		$cadenaJs = <<<javascript
var campo;
if ( !(campo = documento.getElementById('$campoDestino')) )
{
	return;
}
campo.tabIndex = $orden; 
javascript;
		
		return $cadenaJs;
	}//Fin getJsSetOrder
	
	static function getJsSetEnable($campoDestino, $valor='', $tipoComp=null)
	{	
		if($valor===false) //Desactivamos
		{
			$estiloButton = "'none'";
			$desactivacionCampo = 'true';
			$readOnlyCampoTexto = '1';
			$classCampoTexto = 'noEdit';
			$imgCalendario = IMG_PATH_CUSTOM."botones/17off.gif";
			$imgVS = IMG_PATH_CUSTOM."botones/13off.gif";
			$imgJUMP = IMG_PATH_CUSTOM."botones/39off.gif";
			$tabIndex = '-1';
			$disabledSelect = 'disabledSelect ui-button-disabled ui-state-disabled';
		}
		else //Activamos
		{
			$estiloButton = "'-moz-inline-box'";//PAra IE sólo 'inline'
			$desactivacionCampo = 'false';
			$readOnlyCampoTexto = '0';
			$classCampoTexto = 'modify';
			$imgCalendario = IMG_PATH_CUSTOM."botones/17.gif";
			$imgVS = IMG_PATH_CUSTOM."botones/13.gif";
			$imgJUMP = IMG_PATH_CUSTOM."botones/39.gif";
			$tabIndex = '1';
			$disabledSelect = '';
		}
		
		
		if ($tipoComp == 'Radio')
		{
			$cadenaJs = <<<javascript
var campos;
if ( !(campos = documento.getElementsByName('$campoDestino')) )
{
	 return;
}
else
{
	for (var i=0; i<campos.length; i++)
	{
		campos[i].disabled= $desactivacionCampo;
	}
}
javascript;

		}
		else
		{
			$cadenaJs = <<<javascript
var campo;
if ( !(campo = documento.getElementById('$campoDestino')) )
{
	return;
}

if (campo.type=='button') 
{
	campo.style.display = $estiloButton;
}
else if ( 
	(campo.type=='select-one')
	|| (campo.type=='select-multiple')
	|| (campo.type=='checkbox')
	)
{
	// Si tenemos una lista autocomplete
	if (eval('documento.getElementById("combo_$campoDestino")'))
	{
		documento.getElementById('combo_$campoDestino').className='text $classCampoTexto';
		documento.getElementById('combo_$campoDestino').readOnly = $readOnlyCampoTexto;
		classOn = documento.getElementById('comboDown_$campoDestino').className;
		documento.getElementById('comboDown_$campoDestino').className = classOn+' $disabledSelect';
	}
	else
	{
		campo.className='text $classCampoTexto';
		campo.disabled = $desactivacionCampo;
	}
}else if (campo.type=='radio')	
{	
	nombreRadio = campo.name
}
else //Es un text
{
	campo.readOnly = $readOnlyCampoTexto;
	if (campo.tabIndex)
		campo.tabIndex = ($tabIndex)*(Math.abs(campo.tabIndex));
	campo.className='text $classCampoTexto';
	//Calendario
	if ( (cal = documento.getElementById('cal_$campoDestino')) )
		cal.src =  '$imgCalendario';
		
	//Ventana de selección
		// Hay que tratar el botón tooltip de la ventana de selección
		destino = '$campoDestino';	
		vDestino = destino.split('___');
		if (vDestino.length > 1)	
			var idBtn = 'vs_'+vDestino[1]+'___'+vDestino[2];
		else
			var idBtn = 'vs_'+vDestino[0];
	if ( (vs = documento.getElementById(idBtn)) )
		vs.src =  '$imgVS';
		
	
	//Salto
	// Hay que tratar el botón tooltip de la ventana de selección
	if (vDestino.length > 1)	
		var idBtn = 'jump_'+vDestino[1]+'___'+vDestino[2];
	else
		var idBtn = 'jump_'+vDestino[0];
	if ( (jump = documento.getElementById(idBtn)) )
		jump.src =  '$imgJUMP';		
}
javascript;
		}//Fin no es un Radio
		return $cadenaJs;
	}//Fin getJsSetEnable
	
	

	
	static function getJsChecksMarcados($checksMarcados)
	{
		$js='';
		foreach ($checksMarcados as $check) 
			$js.="documento.getElementById('".$check."').checked='true';";
		return $js;
	}

	
	static function getJsSetEstadoModificado($p_campoOrigen)
	{
		// El campo puede tener prefijo (cam__ , ins__) o nada si está en un panel de busqueda. 
		// Si tiene prefijo count(descCampoOrigen)>1 sino no
		// ins___ediCiAnyo___FichaEdicion_0
		$descCampoOrigen = explode('___', $p_campoOrigen);
		
		if(count($descCampoOrigen)<2) return;
		
		// Valor que indicará el estado de la página de la ficha / fila tabla: modificada o insertada
		if ($descCampoOrigen[0]=='cam')
		{
			$valor = 'modificada';
		}
		else if ($descCampoOrigen[0]=='ins')
			$valor = 'insertada';
		else
			$valor = 'nada'; //Jamás llego aquí, pero....
		
		$campo = 'est_'.$descCampoOrigen[2]; // Campo que indicará el estado de la página de la ficha / fila tabla
		$cadenaJs = "eval('documento.forms[\"'+formulario+'\"].".$campo.".value=\"".$valor."\"');\n";
		
		$cadenaJs .= "if (eval(documento.getElementById('".$p_campoOrigen."')) != null) ";
		$cadenaJs .= " aux = documento.getElementById('".$p_campoOrigen."').value;\n";
		
		if ($valor == 'modificada')
		{
		// En el caso de modificada componemos el nombre del campo con el valor anterior			
			$campoAnt = str_replace('cam','ant',$p_campoOrigen);
			$cadenaJs .= "if (eval(documento.getElementById('".$campoAnt."')) != null) ";
		// Nos guardamos el valor anterior a la modificación
			$cadenaJs .= " auxAnt = documento.getElementById('".$campoAnt."').value;\n";
		}		
		
		// Obtenemos en qué panel nos encontramos (fil,lis,edi)
		$cadenaJs .= "vTokens = formulario.split('_');\n";
			$cadenaJs .= "if (vTokens.length > 1);\n";
			$cadenaJs .= "{\n";
				$cadenaJs .= "idFormulario = vTokens[1];\n";
			$cadenaJs .= "};\n";
		$cadenaJs .= "nomObj = idFormulario+'_comp';\n";
		$cadenaJs .= "var objComp = eval('documento.'+nomObj);";
		
		$cadenaJs .="objComp.comprobarModificacion('".$p_campoOrigen."');";
		
		return $cadenaJs;  
	}

	
	static function getJsLanzarFocusChanged()
	{
		//REVIEW: David - Revisar esta función
		$cadenaJs = "";
		$cadenaJs .= "\n//ATENCION: Esto lo arreglaremos posteriormente\n";
		$cadenaJs .= "nombreFormulario ='F_edi';\n";
		$cadenaJs .= "tipoCampo ='cam';\n";
		$cadenaJs .= "idPanel ='FichaEdicion';\n";
		$cadenaJs .= "filaActual =0;\n";
		$cadenaJs .="formulario = eval('document.forms[nombreFormulario]');\n";
		$cadenaJs .="if(formulario!=null){\n";
			$cadenaJs .=" visible = eval('document.getElementById(\'P_edi\').style.display');\n";
			$cadenaJs .=" if(visible!='none'){\n";
			$cadenaJs .="   claseManejadora = formulario.claseManejadora.value;\n";
			$cadenaJs .="   accionAntigua = formulario.action;\n";
			$cadenaJs .="   formulario.action = 'phrame.php?action=focusChanged&claseManejadora='+claseManejadora+'&nomForm='+formulario.name+'&tipoCampo='+tipoCampo+'&idPanel='+idPanel+'&filaActual=-1&filaProxima=0';\n";
			$cadenaJs .="   formulario.target = 'oculto';\n";
			$cadenaJs .="   formulario.submit();\n";
			$cadenaJs .="   formulario.action = accionAntigua;\n";
		$cadenaJs .=" }\n";
		$cadenaJs .="}\n";
		return $cadenaJs;	 
	}

		
	static function getJsFijarFichaActiva($nombrePanel,$claseManejadora,$fichaActiva)
	{
		$cadenaJs = "\n";
		//Necesitamos el panel y la página
		if(($nombrePanel=='edi') or ($nombrePanel=='ediDetalle'))
		{
			$cadenaJs .="nombrePanel ='".$nombrePanel."';\n";
			$cadenaJs .="nombreFormulario ='F_'+'".$nombrePanel."';\n";
			$cadenaJs .="nombreCapa ='P_'+'".$nombrePanel."';\n";
			$cadenaJs .="formulario = eval('document.forms[nombreFormulario]');\n";
			$cadenaJs .="visible = eval('document.getElementById(nombreCapa).style.display');\n";
			$cadenaJs .="if((formulario!=null)&&(visible!='none')){\n";
				$cadenaJs .= "if (".$nombrePanel."_paginacion.hayError() == false){\n";
				$cadenaJs .="document.forms[nombreFormulario].pagActual___".$claseManejadora.".value='".$fichaActiva."';\n";
				$cadenaJs .=$nombrePanel."_paginacion.abrir_pagina(".$fichaActiva.");\n";
			$cadenaJs .="};\n";
			$cadenaJs .="};\n";
		}
		return $cadenaJs;
	}

	
	/**
	 * iGepSmarty::getJsSetBttlState Establece el estado del boton Tooltip
	 * correspondiente a activado (true) o desactivado (false)
	 * @access	public
	 * @param	String	$panel	Indica el panel sobre el que se sitúa el botonTooltip |||
	 * @param	String	$nameBttl	Nombre del boton tooltip
	 * @param	Boolean	$on	True para activar, false para desahabilitar
	 * @return	String	JavaScrip HTML correspondiente al mensaje 
	 */
	static function getJsSetBttlState($idPanel, $nameBttl, $on=true)
	{	
		$nameBttl = trim(strtolower($nameBttl));
		
		$refJS = 'documento.';
		
		switch($nameBttl)
		{
			case 'insertar':
			case 'nuevo':
				$refJS.='bttlInsertar';
			break;
			case 'modificar':
			case 'editar':
				$refJS.='bttlModificar';
			break;
			case 'eliminar':
			case 'borrar':
				$refJS.='bttlEliminar';
			break;
			case 'limpiar':
			case 'restaurar':
				$refJS.='bttlLimpiar';
			break;		
			default:
				return '';
		}
		
		$refJS.="_$idPanel";		
		$cadenaJs = "";
		$cadenaJs .= "var documento = document;";
		$cadenaJs .= "if (document.getElementById('oculto') == null) // estoy en el oculto";
		$cadenaJs .= "\n{ ";
		$cadenaJs .= " documento = parent.document";
		$cadenaJs .= "}\n";
		if ($on)
		{
			$cadenaJs.="$refJS.habilitarBoton();\n";
		}
		else
		{
			$cadenaJs.="$refJS.deshabilitarBoton();\n";
		}	
		return $cadenaJs; 
	} //FIN getJsSetBttlEnable

	static function getJsNuevo($esMaestro,$esDetalle,$actuaSobre)
	{
		$cadenaDetalle='';
		$detalle = 'false';
		$maestro = 'false';
		if ($esMaestro > 0)
			$maestro = 'true';
		if($esDetalle!='')
		{
			$cadenaDetalle='Detalle';
			$detalle = 'true';
		}
		$cadenaJs = '';
		
		// Si no nos llega el parámetro "actuaSobre" es pq no se ha pulsado un botón tooltip.
		// Comprobamos en qué panel nos encontramos para crear un botón que nos permita ejecutar la función javascript insertar() 
		if ($actuaSobre == '')
		{
			$cadenaJs .= 'if(typeof edi'.$cadenaDetalle.'_panel !== "undefined") { boton = "bttlInsertar_edi'.$cadenaDetalle.'"; panel = "edi'.$cadenaDetalle.'"; }';
			$cadenaJs .= 'else if(typeof lis'.$cadenaDetalle.'_panel !== "undefined") { boton = "bttlInsertar_lis'.$cadenaDetalle.'"; panel = "lis'.$cadenaDetalle.'";}';
			
			$cadenaJs .= 'objBoton = new objBTTInsertar(boton,panel,"'.$maestro.'","'.$detalle.'");';
			$cadenaJs .= 'objBoton.insertar();'; 
		}
		else
		{
			// Tenemos el parámetro "actuaSobre", por lo tanto se ha pulsado en un botón tooltip 
			// El parámetro nos indicará el panel que se ha de poner en modo inserción
			if ($actuaSobre == 'ficha') $panel = 'edi'.$cadenaDetalle;
			elseif ($actuaSobre == 'tabla') $panel = 'lis'.$cadenaDetalle;
			$cadenaJs .= 'bttlInsertar_'.$panel.' = new objBTTInsertar("bttlInsertar_'.$panel.'","'.$panel.'","'.$maestro.'","'.$detalle.'");';
			$cadenaJs .= 'bttlInsertar_'.$panel.'.insertar();';
		}		
		return $cadenaJs;
	}

	
	static function getJsOpenWindow($path)
	{
		//REVIEW: David - Tras registrar windows.js en CWVentana, podría quitarse esta declaración
		$cadenaJs ="function Open_Vtna2(pagina,nombre,w,h,toolbar,location,status,menubar,scroll,resizable) {\n";
		$cadenaJs.="LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;\n";
		$cadenaJs.="TopPosition = (screen.height) ? (screen.height-h)/2 : 0;\n";
		$cadenaJs.="settings = 'top='+TopPosition+',left='+LeftPosition+',toolbar='+toolbar+',location='+location+',status='+status+',menubar='+menubar+',scrollbars='+scroll+',resizable='+resizable+',width='+w+',height='+h;\n";
		$cadenaJs.="//Si firefox o Mozilla eliminamos todas las barras, la hacemos modal,dependiente  y que flote sobre el resto\n";
		$cadenaJs.="if (navigator.appCodeName =='Mozilla')\n";
		$cadenaJs.="settings = settings+',directories=no,personalbar=no,minimizable=no,alwaysRaised=yes,modal=yes,dependent=yes';\n";
		$cadenaJs.="win = window.open(pagina,nombre,settings);\n";
		$cadenaJs.="win.focus();\n";
		$cadenaJs.="}";	
		// Mejoras #20344 Posibilidad de abrir varios listados en diferentes ventanas emergentes, nombre ventana aleatorio
		$x = rand(1,10000);
        $nomVentana = "ventana".$x;
        $cadenaJs.="Open_Vtna2('".$path."','".$nomVentana."',700,500,'no','no','no','no','yes','yes');";
		return $cadenaJs;
	}

	
	/************ MODAL **********************/
	//Creamos el metodo para abrir la ventana
	static function getJsOpenModalWindow($path,$returnPath,$nomForm,$width,$height)
	{

		$cadenaJs = "url = '".$path."';";		
		$cadenaJs .= "paramsSource = {};";		
	    $cadenaJs .= "paramsSource.formulario = '".$nomForm."';";
	    $cadenaJs .= "paramsSource.returnPath = '".$returnPath."';";	    
	    $cadenaJs .= "openModal(url, paramsSource,$width,$height);";
		return $cadenaJs;
	}
	
	//Creamos el metodo para cerrar la ventana
	static function getJsCloseModalWindow()
	{
		$cadenaJs = "parent.window.top.close();";
		return $cadenaJs;
	}
	/************ MODAL **********************/
		
	/**
	 * Método que genera el javascript para actualizar un componente CWCheckBox desde una acción de interfaz.
	 * @param string idCheck	nombre del check
	 * @param boolean check	Checked si o no
	 * @param string hiddenName Nombre del hidden creado por el CWCheckBox
	 * @param string value	Valor del si o no 
	 * @return none
	 */
	public static function getJsSetCheck($idCheck, $check, $hiddenName, $value) {
	
		//Actualizamos el CheckBox	
		$cadenaJs = "eval('parent.document.forms[\"'+formulario+'\"].";
		$cadenaJs.= $idCheck.".checked=";
		$cadenaJs.= intval($check)."');\n";	
	
		//Lanzamos la funcion que actualiza el hidden
		$cadenaJs.= IgepSmarty::getJsSetCampoTexto($hiddenName,$value);
		return $cadenaJs;
	}


}//Fin Class IgepSmarty
?>
