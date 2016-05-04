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
 * IgepArbol es una clase que contiene la definición de la estructura del arbol
 * y su comportamiento. 
 * 
 * @version	$Id: IgepArbol.php,v 1.33 2010/01/29 13:31:23 gaspar Exp $
 * @author David: <pascual_dav@gva.es> 
 * @author Keka: <bermejo_mjo@gva.es>
 * @author Vero: <navarro_ver@gva.es>
 * @author Raquel: <borjabad_raq@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 */ 

class IgepArbol
{
	
	var $v_defArbol;
	var $maxId;
	var $cadXML;
	var $str_claseManejadora;
	var $tipoNodoSeleccionado;	
	var $str_tituloPanel;
	
	
	/**
    * Constructor
    */		
	public function __construct() {

		$this->IgepArbol();
	}
	
    /**
    * Constructor
    */		
	public function IgepArbol() {
		
		$this->v_defArbol = array();
	}
	

	/**
	 * Este método permite al programador indicar a Igep que el árbol tendrá una raiz.
	 * @param string tipo indica el tipo de la raiz, es decir el identificador.
	 * @param string etiqueta la etiqueta que aparecerá en pantalla sobre esta raiz.
	 * @param string tipoDespliegue indica la forma como se obtendrán los hijos de esta raiz. Por consulta SQL tipo 'SELECT' o  de fuente estática 'LISTA'.
	 * @param array  datosDespliegue Este es un parámetro complejo. Sirve para indicar información sobre los hijos que se van crear al desplegar la raiz. El array se compone de:
	 * <ul>
	 * <ui> En el caso de ser un despliegue a partir de una consulta SQL (SELECT); se compone de dos campos. El primero indica el identificador de los hijos (su tipo de nodo) y el segundo la consulta a partir de la cual se obtienen.</ui>
	 * <ui>En el caso de ser un despliegue a partir de una lista fija (LISTA); se compone de tantos items como tenga la lista, eso si, el array debe ser asociativo dejando como indice el tipo de cada hijo y como valor la descripción que se quiere visualizar.</ui>
	 * </ul>
	 * @param array dsn parámetro opcional que sirve para indicar una conexión alternativa a la propia del panel para el despliegue de dicha raiz.
	 * 
	 */
	function addNodoRaiz($tipo, $etiqueta, $modoDespliegue, $despliegue, $dsnAlternativo = '')
	{
		$this->v_defArbol[$tipo]['etiqueta'] = $etiqueta;
		$this->v_defArbol[$tipo]['raiz'] = 1;
		$this->addNodoRama($tipo,$modoDespliegue,$despliegue,$dsnAlternativo);
		return 0;		 
	}

    /**
    * Este método permite al programador indicar a como se crea y se despliega una rama.
    * @param string tipo indica el tipo de la rama, es decir el identificador por el que responderá.
    * @param string tipoDespliegue indica la forma como se obtendrán los hijos de esta rama. Por consulta SQL tipo 'SELECT' o  de fuente estática 'LISTA'.
    * @param array  datosDespliegue Este es un parámetro complejo. Sirve para indicar información sobre los hijos que se van crear al desplegar la rama. El array se compone de:
    * <ul>
    * <ui>En el caso de ser un despliegue a partir de una consulta SQL (SELECT); se compone de dos campos. El primero indica el identificador de los hijos (su tipo de nodo) y el segundo la consulta a partir de la cual se obtienen.</ui>
    * <ui>En el caso de ser un despliegue a partir de una lista fija (LISTA); se compone de tantos items como tenga la lista, eso si, el array debe ser asociativo dejando como indice el tipo de cada hijo y como valor la descripción que se quiere visualizar.</ui>
    * </ul>
    * @param array dsn parámetro opcional que sirve para indicar una conexión alternativa a la propia del panel para el despliegue de dicha rama. 
    * 
    */	
	function addNodoRama($tipo,$modoDespliegue,$despliegue,$dsnAlternativo = '')
	{
		if ($modoDespliegue == 'SELECT')
		{
			$this->v_defArbol[$tipo]['despliegue']['tipoHijos'] = $despliegue[0];			
			$this->v_defArbol[$tipo]['despliegue']['consulta'] = $despliegue[1];
			if(isset($despliegue[2]))
				$this->v_defArbol[$tipo]['despliegue']['dependencia'] = $despliegue[2];
			$this->v_defArbol[$tipo]['conexion'] = $dsnAlternativo;
		}
		else
		{			
			foreach($despliegue as  $etiquetaHijos => $tipoHijos)
				$this->v_defArbol[$tipo]['despliegue']['tipoHijos'][$etiquetaHijos] = $tipoHijos;				 
		}
		return 0;						
    }

    /**
    * Este método permite al programador indicar que cierto tipo de nodo tiene asociada una representación en 
    * un panel asociado al arbol
    * @param string tipo indica el tipo o identificador por el que responderá.
    * @param string claseManejadora Indaca el nombre de la clase que manejará el panel que se quiere representar
    * @param array  dependencia Este array indica los campos del arbol que se deben tener en cuenta antes de mostrar el panel. 
    * @param array tituloPanel El titulo que mostará el panel. En este título se pueden incluir referencias a los campos obtenidos en el arbol.
    */  	
	function setNodoPanel($tipo,$claseManejadora,$dependencia,$tituloPanel)
	{
		$this->v_defArbol[$tipo]['enPanel'] = 1;
		$this->v_defArbol[$tipo]['claseManejadora'] = $claseManejadora;
		if($dependencia!="" and count($dependencia)>0)
			$this->v_defArbol[$tipo]['dependencia'] = $dependencia;		
		$this->v_defArbol[$tipo]['tituloPanel'] = $tituloPanel;		
		return 0;
	}
	
    /**
    * Genera el XML inicial
    * @access private
	*/
	public function generaXML($str_claseActual) {
		//Guardamos la clase Manejadora del arbol
		$this->str_claseManejadora = $str_claseActual;
		//Creamos el documento XML		
		
		$doc = new DOMDocument('1.0');
		$nodoInicial = $doc->createElement('igepArbol');		
		$nodoInicial = $doc->appendChild($nodoInicial);
		$nodoInicial->setAttribute('id','0');
		$id = 1;
		foreach($this->v_defArbol as $tipoNodo => $descNodo){			
			if($descNodo['raiz']==1){				
				$elemento = $doc->createElement('igepNodo');						
				$nodo_nuevo = $nodoInicial->appendChild($elemento);			
				$nodo_nuevo->setAttribute('id', "$id");
				$nodo_nuevo->setAttribute('texto',utf8_encode($descNodo['etiqueta']));
				$nodo_nuevo->setAttribute('tipo',$tipoNodo);												
				$nodo_nuevo->setAttribute('enlace', 'phrame.php?action='.$this->str_claseManejadora."__abrirRamaArbol&amp;id=$id");
				$nodo_nuevo->setAttribute('desplegable','1');
				++$id;		
			}
		}		
		$this->cadXML = $doc->saveXML();
		$this->maxId = $id; 		
		return 0; 		
	}
	
    /**
    * devuelve el XML actual.
    * @access private
    */
	public function getXML() {

		return $this->cadXML; 
	}
	
    /**
    * Método que se lanza al pulsar un usuario sobre una rama para expandirla.
    * @access private 
    */
	public function abrirRamaArbol() {
		//Recogemos el id	
		$idNodoSeleccionado = $_REQUEST['id'];			  		 	
		//Creamos el dom
		$dom = DOMDocument::loadXML($this->cadXML,
			DOMXML_LOAD_PARSING + //0
	  	    DOMXML_LOAD_COMPLETE_ATTRS + //8
			DOMXML_LOAD_SUBSTITUTE_ENTITIES + //4
			DOMXML_LOAD_DONT_KEEP_BLANKS //16 
		);
		
		//REVIEW: DAVID Seguir a partir de aquí
		
		//Buscamos el elemento seleccionado en el arbol		
   	    $xpathXML = new DOMXPath($dom);
   	    $v_nodos = $xpathXML->evaluate("//*[@id = '$idNodoSeleccionado']", $dom);
        $nodoPadre = $v_nodos->item(0);   		

        if($nodoPadre!=null) {
            //Cogemos el tipo de nodo			
            $this->tipoNodoSeleccionado = $nodoPadre->getAttribute('tipo');								
            //Cogemos la descripcion
            $descTipoNodo = $this->v_defArbol[$this->tipoNodoSeleccionado];
            //Comprobamos si se tiene que desplegar y, si es así, si está desplegado o no. 
            $desplegado = $nodoPadre->getAttribute('desplegado');
            if((isset($descTipoNodo['despliegue'])) and (is_array($descTipoNodo['despliegue']))) // MOD(ENLAZA): Recarga ramas aunque ya se hayan desplegado
//            if((isset($descTipoNodo['despliegue'])) and (is_array($descTipoNodo['despliegue'])) and ($desplegado!='1'))    		   		   		
                $this->desplegarRamaXml($dom,$nodoPadre,$descTipoNodo);
			//Si tiene representación en panel tenemos que lanzar el metodo de representacion
			if($descTipoNodo['enPanel']==1)
                $this->mostrarEnPanel($nodoPadre,$descTipoNodo);
			
            //Buscamos el seleccionado anterior
			$xpathXMLSeleccionadoAnterior = new DOMXPath($dom);
			$v_nodoSeleccionado = $xpathXMLSeleccionadoAnterior->evaluate("//*[@seleccionado = '1']", $dom);
			$nodoSeleccionadoAnt = $v_nodoSeleccionado->item(0);			
			if($v_nodoSeleccionado->length>0) // MOD(ENLAZA): Había una 's' de mas - v_nodoSeleccionados
				$nodoSeleccionadoAnt->removeAttribute('seleccionado');
			//Marcamos el nodo como seleccionado
			$nodoPadre->setAttribute('seleccionado','1');
			
			$xpathXMLRamaSeleccionadosAnteriores = new DOMXPath($dom);
			$xpresultSeleccionado = $xpathXMLRamaSeleccionadosAnteriores->evaluate("//*[@ramaSeleccion = '1']", $dom);
			foreach ($xpresultSeleccionado as $nodoSelecAnt)
				$nodoSelecAnt->removeAttribute('ramaSeleccion');
			$nodoPadreAux = $nodoPadre;
			do
			{
				if(get_class($nodoPadreAux)=='DOMElement')
					$nodoPadreAux->setAttribute('ramaSeleccion', '1');
			} while ($nodoPadreAux = $nodoPadreAux->parentNode);
   	    }//Fin de si el padre no es nulo    		
		$this->cadXML = $dom->saveXML();
		return 0; 
	}//Fin de abrirRamaArbol 
	
	/**
     * Método que contiene todos los pasos para poder desplegar una ráma
     * en el XML actual.
     * @access private
     */
	public function desplegarRamaXml(& $dom,& $nodoPadre,$descTipoNodo) {
		
		$res = $this->generaHijos($nodoPadre, $descTipoNodo);			
		$idHijo = $this->maxId;			
		// MOD(ENLAZA): Eliminamos antiguos hijos
		while ($nodoPadre->hasChildNodes()) {
			$nodoPadre->removeChild( $nodoPadre->firstChild );
		}
		//Creamos los hijos
		foreach ($res as $datosHijo) 
		{
			++$idHijo;
			$elemento = $dom->createElement('igepNodo');
			$nodo_nuevo = $nodoPadre->appendChild($elemento);
			$nodo_nuevo->setAttribute('id', $idHijo);
			//Colocamos el enlace
			$nodo_nuevo->setAttribute('enlace', 'phrame.php?action='.$this->str_claseManejadora."__abrirRamaArbol&amp;id=$idHijo");
			//Dependiendo del la fuente (viene de SELECT o LISTA el tipo de los hijos puede cambiar o no)
			if(is_array($descTipoNodo['despliegue']['tipoHijos']))
				$nodo_nuevo->setAttribute('tipo', $descTipoNodo['despliegue']['tipoHijos'][$datosHijo['etiqueta']]);				
			else
				$nodo_nuevo->setAttribute('tipo', $descTipoNodo['despliegue']['tipoHijos']);
			//Recuperamos todos los campos clave
			foreach($datosHijo as $campo => $valor)
			{
				if($campo=='etiqueta')
					$nodo_nuevo->setAttribute('texto', utf8_encode($datosHijo['etiqueta']));
				else
					$nodo_nuevo->setAttribute(utf8_encode($campo), utf8_encode($valor));
			}
			//Para indicar si los nodos hijos se despliegan o no
			//Si es una lista tenemos que comprobar si cada uno de los hijos se despliegan, ya que son de diferentes tipos
			if(is_array($descTipoNodo['despliegue']['tipoHijos']))
			{
				foreach($descTipoNodo['despliegue']['tipoHijos'] as $tipoNodosHijoLista){
					if(isset($this->v_defArbol[$tipoNodosHijoLista]['despliegue']))
						$nodo_nuevo->setAttribute('desplegable','1');
					else
						$nodo_nuevo->setAttribute('desplegable','0');
				}
			}//Fin de si es una lista
			//Si es una consulta con comprobarlo una vez sobra
			else
			{
				if(isset($this->v_defArbol[$descTipoNodo['despliegue']['tipoHijos']]['despliegue']))
					$nodo_nuevo->setAttribute('desplegable','1');
				else
					$nodo_nuevo->setAttribute('desplegable','0');
			}
		}//Fin de foreach de la consulta
		$this->maxId = $idHijo;
		$nodoPadre->setAttribute('desplegado','1');
		return 0;
	}//Fin de deplegarRama
	
    /**
    * Dada una rama para desplegar obtiene los nodos hijos correspondientes
    * @access private 
    */
	public function generaHijos($nodoPadre, $descTipoNodo){							
		if(isset($descTipoNodo['despliegue']['consulta'])){		
			$consulta = $descTipoNodo['despliegue']['consulta'];					
			//Tenemos que bucar los parámetros que faltan por rellenar y rellenarlos
			if (is_array($descTipoNodo['despliegue']['dependencia']))
				foreach ($descTipoNodo['despliegue']['dependencia'] as $campoDependiente){					
					$atributoClave = $nodoPadre->getAttribute($campoDependiente);										
					while($atributoClave==null or $nodoPadre->tagname == 'igepArbol'){
						$nodoPadre = $nodoPadre->parentNode;
						$atributoClave = $nodoPadre->getAttribute($campoDependiente);
					}
					if($atributoClave!=null)
						$consulta = str_replace("%$campoDependiente%",utf8_decode($atributoClave),$consulta);
					else								
						die('Se busca el parámetro dependiente '.$campoDependiente.' y no se encuentra entre los parametros de los padres del nodo pulsado');
				}//Fin de foreach
			//Si tiene conexion propia nos conectamos al dsn, sino la que tiene por defecto el panel											
			if(isset($descTipoNodo['conexion']) and $descTipoNodo['conexion']!='')				
				$dsn = $descTipoNodo['conexion'];
			else{
				$obj_conexionClase = IgepSession::dameVariable($this->str_claseManejadora,'obj_conexion');
				$dsn = $obj_conexionClase->getDsn();				
			}
			$conexion = new IgepConexion($dsn);
			$res = $conexion->consultar($consulta);
		}//Fin de es una Select
		else{
			$i=0;
			foreach($descTipoNodo['despliegue']['tipoHijos'] as $etiquetaHijo => $tipoHijo){
				$res[$i]['etiqueta'] = $etiquetaHijo;
				++$i;
			}
		}
		return $res;
	}//Fin de generaHijos
	
    /**
    * Se encarga de instanciar la clase que se quiere visualizar en el panel anexo y
    * lanzar la busqueda sobre dicho panel.
    * @access private 
    */
	public function mostrarEnPanel($nodoPadre,$descTipoNodo){
		$titulo = $descTipoNodo['tituloPanel'];
		//Si el nodo tiene dependencia tenemos que montar un array para pasarlo como el $_REQUEST al buscar de esa clase							
		if(isset($descTipoNodo['dependencia'])) {
			foreach ($descTipoNodo['dependencia'] as $campoDependiente){									
				$atributoClave = $nodoPadre->getAttribute($campoDependiente);										
				while($atributoClave==null or $nodoPadre->tagname == 'igepArbol'){					
					$nodoPadre = $nodoPadre->parentNode;
					$atributoClave = $nodoPadre->getAttribute($campoDependiente);
				}
				if($atributoClave!=null) {
          $atributoClave = utf8_decode($atributoClave);
					$titulo = str_replace("%$campoDependiente%",$atributoClave,$titulo);
					$camposBusqueda[$campoDependiente] = $atributoClave;
				}
			}//Fin de foreach			
		}//Fin de dependencia
		$this->str_tituloPanel = $titulo;		
		$_REQUEST = $camposBusqueda;
		IgepSession::borraPanel($descTipoNodo['claseManejadora']);
		//REVIEW: David. Toni cree que mejor privado. revisar más modos de entrada
		$objeto = new $descTipoNodo['claseManejadora'];
        //Creamos de nuevo la instancia interna de comunicación porque se pierde el matching
        $objeto->comunica = new IgepComunicacion($objeto->v_descCamposPanel);        
        $objeto->buildQuery();
		$objeto->refreshSearch();
		$objeto->limpiarInstancia();
		IgepSession::guardaPanel($descTipoNodo['claseManejadora'],$objeto);
		return 0;
	}

    /**
    * Acción que se ejecuta al pulsar sobre el botón cancelar de un arbol
    * @access private 
    */
	public function cancelarArbol(){
		foreach ($this->v_defArbol as $panel){
			if(isset($panel['claseManejadora']))				
				IgepSession::borraPanel($panel['claseManejadora']);				
		}
		return 0;
	}

    /**
    * Borra el contenido de un arbol
    * @access private 
    */	
	public function limpiarArbol($claseActual) {
		IgepSession::borraVariable($claseActual,"obj_arbol");
	}
}//Fin de IgepArbol
?>