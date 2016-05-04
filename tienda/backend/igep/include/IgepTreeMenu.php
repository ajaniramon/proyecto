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
require_once( 'TreeMenu.php' );


// +----------------------------------------------------------------------------+
// | Las clases de estos ficheros, se basan en las clases HTML_TreeMenu			|
// | y HTML_TreeNode de Richard Heyes  <http://www.phpguru.org/>, se realiza	|
// | una extensión a partir de las mismas para poder adecuadarlas al proyecto	|
// | IGEP y facilitar después la actualización a nuevas versiones del paquete	|
// | original 																	|															|
// +----------------------------------------------------------------------------+
// @author  David Pascual <pascual_dav@gva.es>
// @author  Veronica Navarro <navarro_ver@gva.es>
// @author  Maria Jose Bermejo <bermejo_mjo@gva.es>
// @author  Raquel Borjabad <borjabad_raq@gva.es>
// @author  Antonio Felix Ferrando <felix_ant@gva.es>
// @package HTML_IgepTreeMenu
// $Id: IgepTreeMenu.php,v 1.14 2009-07-16 14:50:03 gaspar Exp $



/* --------------------------------------------------------------------------- */
/**
* HTML_IgepTreeMenu Class
*
* Clase PHP para gestionar árboles dinámicos de expansión en PHP y javascript
* Para una correcta visualización se recomienda navegadores compatibles con la
* API DOM del W3C, es decir, IExplorer 5.5 o superior o navegadores de la
* familia Mozilla (Firefox, Mozilla...).
* Esta clase se basa en HTML_TreeMenu de Richard Heyes y Harald Radi
* de ella hereda todas las propiedades y extiende algunas de ellas para
* integrarse mejor en el proyecto IGEP
*/
class HTML_IgepArbol extends HTML_TreeMenu
{
	/**
	* Constructor
	*
	* @access public
	*/
	function HTML_IgepArbol()
	{
		parent::HTML_TreeMenu();
	}//Fin HTML_IgepArbol
	
	
	/**
	 * Construye un arbol recogiendo la información a partir
	 * del directorio de ficheros indicado en el argumento path
	 * @param  string  $path Cadena con la ruta al directorio base a explorar
	*/
	function arbolFicheros($path=".") 
	{
		$this->addItem($this->_recorreDirectorio($path));
	}//Fin arbolFicheros	
	

	/**
	 * Metodo privado para el recorrido recursivo de un directorio
	 * creando los nodos del arbol
	 * @access private
	 * @param  object $node El nodo a añadir, de la clase HTML_TreeNode object.
	 * @return object	Referencia al nuevo nodo añadido al árbol
	 * @param  string  $path Cadena con la ruta al directorio base a explorar
	*/	 
	function _recorreDirectorio($path) 
	{
		if (!$dir = opendir($path)) 
		{
			return false;
		}
		$files = array();
		//Creamos un nodo
		$nodo   = new HTML_IgepNodo (
			array( //Vector de preferencias
				'text' => basename($path), //Texto del nodo
				//'link' => basename($path), //Enlace
				'icon' => "folder.gif", //Icóno minimizado
				'expandedIcon' => "folder-expanded.gif", //Icono Expandido
				'expanded' => false //Si aparece expandido o no
			),
			array(
				'onclick' => "",
			)
		);//Fin nodo	
		while (($file = readdir($dir)) !== false)//Mientras queden ficheros en el directorio...
		{
			if ($file != '.' && $file != '..') //Si tratamos un fichero...
			{
				if (@is_dir("$path/$file")) 
				{
					$addnode = &$this->_recorreDirectorio("$path/$file");
				}
				else
				{
					$addnode = new HTML_IgepNodo (
						array( //Vector de preferencias
							'text' => $file, //Texto del nodo
							'link' => $path."/".$file, //Enlace
							'icon' => "file.gif", //Icóno minimizado
							'expandedIcon' => "folder-expanded.gif", //Icono Expandido
							'expanded' => false //Si aparece expandido o no
						)
					);
				}
				$nodo->anyadeNodoHijo($addnode);
			}
		}
		closedir($dir);
		return $nodo;
	}//Fin _recorreDirectorio
	
		
	/**
	 * Añade un nodo al árbol, se sobrecarga el metodo addItem de
	 * TreeMenu para asociar la CSS de la guia de estilo al nodo añadido
	 * @access public
	 * @param  object $node El nodo a añadir, de la clase HTML_TreeNode object.
	 * @return object	Referencia al nuevo nodo añadido al árbol
	*/ 
	function &anyadeNodoHijo(&$node)
	{
		$claseCSS = "treeIgep";
		if ($node->nodoSelected == true) $claseCSS .="Selected";
		$node->cssClass = $claseCSS;
		$this->items[] = &$node;
		return $this->items[count($this->items) - 1];
	}//Fin anyadeNodoHijo	
	
	
	/**
	 * Crea un nodo con el texto y el enlace que se pasan como parámetros
	 * y lo añade como hijo del nodo
	 * @access public
	 * @param  string $texto El texto del nodo a añadir
	 * @param  string $enlace El enlace o link del nodo si es que @default null
	 * @return object	Referencia al nuevo nodo añadido al árbol
	*/
	function &creaNodoHijo($texto, $enlace="#")
	{
		if ($enlace!="#") //Si NO tiene enlace, no hay icono de fichero
			$icono ="file.gif";
		else
			$icono ="";
		$node = new HTML_IgepNodo 
		(
			array
			( //Vector de preferencias
				'text' => $texto, //Texto del nodo
				'link' => $enlace, //Enlace
				'icon' => $icono, //Icóno minimizado
				'expandedIcon' => $icono, //Icono Expandido				
				'expanded' => false //Si aparece expandido o no							
			)
		);
		$node->cssClass="treeIgep";
		$this->items[] = &$node;
		return $this->items[count($this->items) - 1];
	}//Fin creaNodoHijo
	
	
	/**
	 * Construye un arbol a partir de la estructura XML pasada como argumento
	 * @param  string  $tipo	Indica si debe representarse como arbol o como un listBox
	*/ 
	function arbolXML ($xml)
	{
		$docXML = DOMDocument::loadXML
		(
			$xml,
			DOMXML_LOAD_PARSING + //0
	  		DOMXML_LOAD_COMPLETE_ATTRS + //8
			DOMXML_LOAD_SUBSTITUTE_ENTITIES + //4
			DOMXML_LOAD_DONT_KEEP_BLANKS //16 
		);
		
		//Raiz del documento XML
		$raizXML = &$docXML->documentElement;
		
		$this->_XML2Arbol($this, $raizXML);	
	}//Fin arbolXML
	
	
	/**
	 * Funcion recursiva, a partir de una rama XML la recorre construyendo
	 * el árbol en PHP.
	 * @access private
	 * @param  object 	$nodoArbol	Nodo del arbol PHP
	 * @param  object 	$nodoXML	DOMNode del PHP XML
	*/ 
	function _XML2Arbol(&$nArbol, &$nodoXML)
	{
		$nodoHijoXML = null;
		$nodoArbolHijo = null;
		
		if ($nodoHijoXML = $nodoXML->firstChild)//Si tiene hijos...
		{
			while ($nodoHijoXML) 
			{
				//Tratamos el NODO hijo 
				$nodoArbolHijo = &$nArbol->anyadeNodoHijo(HTML_IgepArbol::_XML2Nodo($nodoHijoXML));
				
				//Tratamos recursivamente el nodo
				HTML_IgepArbol::_XML2Arbol($nodoArbolHijo, $nodoHijoXML);
				//Avanzamos al sigueinte hijo
				$nodoHijoXML = $nodoHijoXML->nextSibling;
			}
		}
	}//Fin _XML2Arbol
	
	
	
	
	/**
	 * Extrae la información del doto XML que debe aplicar al nodo del Arbol
	 * 
	 * @access private
	 * @param  object	$nodoArbol	Nodo del arbol PHP creado
	 * @param  object 	$nodoXML	DOMNode del PHP XML
	 * @return object 	$nodoArbol	Nodo del arbol PHP creado
	*/	
	function _XML2Nodo(&$nodoXML)
	{	
		//Obtenemos los atributos del XML
		$textoNodo = utf8_decode ($nodoXML->getAttribute("texto"));

		//Posible solución al problema de los saltos de línea,
		//o bien se "escapan" los saltos
		//o bien se transforma en entidades HTML
		//$textoNodo = str_replace("\n",'',$textoNodo);
		//$textoNodo = htmlentities($textoNodo, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
		
		$seleccionado =null;
		//Obtenemos los atributos del XML
		$seleccionado = $nodoXML->getAttribute("seleccionado");
		$desplegable = $nodoXML->getAttribute("desplegable");
		$nodoId = $nodoXML->getAttribute("id");
		$ramaSeleccion = $nodoXML->getAttribute('ramaSeleccion');
	
		
		$enlaceNodo = $nodoXML->getAttribute("enlace");
		if($enlaceNodo=="")	$enlaceNodo = "#";
		
		$iconoFichero ="file.gif";//Icono para los ficheros				
		$expandido = false;
		//Si el nodo es desplegable por negocio, o tiene hijos...
		if($desplegable == '1')
			$iconoFichero ='';
		if (
			($seleccionado=='1') ||
			($nodoId=='1') ||
			($ramaSeleccion=='1')
			) 
		{									
			$expandido = true;
		}		
		if ($nodoXML->hasChildNodes()) $iconoFichero ="";
		
		//Construimos el nodo 
		$nodoArbol = new HTML_IgepNodo 
		(
			array( //Vector de preferencias
				'text' => $textoNodo, //Texto del nodo
				'link' => $enlaceNodo, //Enlace
				'icon' => $iconoFichero, //Icóno minimizado
				'expandedIcon' => "", //Icono Expandido				
				'expanded' => $expandido //Si aparece expandido o no
			)
		);

		if ( ($seleccionado !="no") && ($seleccionado !="false") && ($seleccionado != null) )
		$nodoArbol->nodoSeleccionado = true;
		
		return $nodoArbol;
	}//Fin _XML2Nodo	
	
	
	
	
	/**
	* Imprime el arbol en pantalla
	*
	* @param  string  $tipo	Indica si debe representarse como arbol o como un listBox
	*/	
	function printMenu ($tipo="arbol")
	{
		if ($tipo=="arbol")
		{
			$menuPresenta = new HTML_TreeMenu_DHTML($this, array('images' => IMG_PATH_CUSTOM.'arbol', 'isDynamic'=>true, 'defaultClass' => 'treeIgep'));
		}
		else //if ($tipo=="lista")
		{
			$menuPresenta  = new HTML_TreeMenu_Listbox($this, array('linkTarget' => '_self'));
			$menuPresenta->promoText  = 'Seleccione...';
			$menuPresenta->submitText = 'Ir';
		}
		$menuPresenta->printMenu();
	}// Fin Imprime el arbol
	
	
	function generaMenu ($tipo="arbol")
	{
		if ($tipo=="arbol")
		{
			$menuPresenta = new HTML_TreeMenu_DHTML($this, array('images' => IMG_PATH_CUSTOM.'arbol', 'isDynamic'=>true, 'linkTarget'=>'oculto' ,'defaultClass' => 'treeIgep'));
		}
		else //if ($tipo=="lista")
		{
			$menuPresenta  = new HTML_TreeMenu_Listbox($this, array('linkTarget' => 'oculto'));
			$menuPresenta->promoText  = 'Seleccione...';
			$menuPresenta->submitText = 'Ir';
		}
		$codigo = $menuPresenta->toHTML();
		return $codigo;
	}
	
}//Fin Class HTML_IgepTreeMenu
/* --------------------------------------------------------------------------- */





/* --------------------------------------------------------------------------- */
/**
* HTML_IgepTreeNode class
*
* Esta clase se basa en HTML_TreeNode de Richard Heyes y Harald Radi
* de ella hereda todas las propiedades y extiende algunas de ellas para integrarse
* mejor en el proyecto IGEP
*/
class HTML_IgepNodo extends HTML_TreeNode
{
	/**
	 * Boolean, indica si el noo esta seleccionado o no
	*/
	var $nodoSeleccionado = null; 
	
		
	/**
	* Constructor
	*
	* @access public
	*/
	function HTML_IgepNodo($options = array(), $events = array())
	{
		parent::HTML_TreeNode($options, $events);
	}
	
	
	/**
	* Añade un novo hijo al nodo actual
	*
	* @access public
	* @param  object $nodo El nuevo nodo jijo
	*/
	function &anyadeNodoHijo(&$node)
	{
		$node->parent  = &$this;
		$this->items[] = &$node;
		if ($node->ensureVisible)
		{
			$this->_ensureVisible();
		}
		
		$claseCSS = "treeIgep";
		if ( ($this->cssClass=="treeIgep") || ($this->cssClass=="treeIgepSelected") )
			$claseCSS = "nodoIgep1";
		else if ( ($this->cssClass=="nodoIgep1") || ($this->cssClass=="nodoIgep1Selected") )
			$claseCSS = "nodoIgep2";
		else if ( ($this->cssClass=="nodoIgep2") || ($this->cssClass=="nodoIgep2Selected") )
			$claseCSS = "nodoIgep2";
		else
			$claseCSS = "treeIgep";
		
		//Si el nodo esta seleccionado alteramos su CSS
		if ($node->nodoSeleccionado == true) $claseCSS .="Selected";
		
		$node->cssClass = $claseCSS;
		return $this->items[count($this->items) - 1];
	}//Fin 
	

	/**
	* Crea un nodo con el texto y el enlace que se pasan como parámetros
	* y lo añade como hijo del nodo
	*
	* @access public
	* @param  string $texto El texto del nodo a añadir
	* @param  string $enlace El enlace o link del nodo si es que @default null
	* @return object	Referencia al nuevo nodo añadido a la rama
	*/
	function &creaNodoHijo($texto, $enlace="#")
	{
		if ($enlace!="#") //Si NO tiene enlace, no hay icono de fichero
			$icono ="file.gif";
		else
			$icono ="";
			
		$node = new HTML_IgepNodo
			(
				array
				( //Vector de preferencias
					'text' => $texto, //Texto del nodo
					'link' => $enlace, //Enlace
					'icon' => $icono, //Icóno minimizado
					'expandedIcon' => "", //Icono Expandido					
					'expanded' => false //Si aparece expandido o no
				)
			);
		$node->parent  = &$this;
		$this->items[] = &$node;
		if ($this->cssClass=="treeIgep")
			$node->cssClass="nodoIgep1";
		else if ($this->cssClass=="nodoIgep1")
			$node->cssClass="nodoIgep2";
		else if ($this->cssClass=="nodoIgep2")
			$node->cssClass="nodoIgep2";
		else 
			$node->cssClass="treeIgep";
		return $this->items[count($this->items) - 1];
	}//Fin creaNodoHijo
	
	
	
}//Fin class HTML_IgepNodo
/* --------------------------------------------------------------------------- */


?>