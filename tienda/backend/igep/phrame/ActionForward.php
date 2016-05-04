<?php
/**
 * The ActionForward class represents a destination to which the
 * ActionController might be directed to forward or redirect to, as a result of
 * the processing activities of an Action class. Instances of this class may be
 * created dynamically as necessary, or configured in association with an
 * ActionMapping instance for named lookup of potentially multiple destinations
 * for a particular ActionMapping instance.
 *
 * An ActionForward has the following minimal set of properties. Additional
 * properties can be provided as needed by subclasses.
 * <ul>
 * <li><b>name</b> - Logical name by which this instance may be looked up in
 * relationship to a particular ActionMapping.</li>
 * <li><b>path</b> - The absolute or relative URI to which control should be
 * forwarded or redirected.</li>
 * <li><b>redirect</b> - Set to 1 if the ActionController should kill the
 * session and redirect on the URI. [0]</li>
 * </ul>
 *
 * @author	Arnold Cano
 * @version	$Id: ActionForward.php,v 1.3 2007-06-01 11:27:42 afelixf Exp $
 */
class ActionForward extends Object
{
	/**
	 * @var	string
	 */
	var $_name;
	/**
	 * @var	string
	 */
	var $_path;
	/**
	 * @var	integer
	 */
	var $_redirect = 0;
	
	/**
	 * @var	array
	 */
	var $_saco = null;

	/**
	 * Create an ActionForward with the specified values.
	 *
	 * @access	public
	 * @param	string	$name
	 * @param	array	$forward
	 */
	function ActionForward($name, $forward=null)
	{
		$this->setName($name);
		if ($forward==null)
		{
			$forward[_PATH]='';
			$forward[_REDIRECT]=0;
		}
		$this->setPath($forward[_PATH]);
		$this->setRedirect($forward[_REDIRECT]);
		$this->_saco = array();
	}
	/**
	 * Get the name of the ActionForward.
	 *
	 * @access	public
	 * @return	string
	 */
	function getName()
	{
		return $this->_name;
	}
	/**
	 * Set the name of the ActionForward.
	 *
	 * @access	public
	 * @param	string	$name
	 */
	function setName($name)
	{
		$this->_name = $name;
	}
	/**
	 * Get the path of the ActionForward.
	 *
	 * @access	public
	 * @return	string
	 */
	function getPath()
	{
		return $this->_path;
	}
	/**
	 * Set the path of the ActionForward.
	 *
	 * @access	public
	 * @param	string	$path
	 */
	function setPath($path)
	{
		$this->_path = $path;
	}
	/**
	 * Get the redirect flag of the ActionForward.
	 *
	 * @access	public
	 * @return	integer
	 */
	function getRedirect()
	{
		return $this->_redirect;
	}
	/**
	 * Set the redirect flag of the ActionForward.
	 *
	 * @access	public
	 * @param	integer	$redirect
	 */
	function setRedirect($redirect)
	{
		$this->_redirect = $redirect;
	}
	
	/**
	 * Fija un valor en la coleccion
	 *
	 * @access	public
	 * @param	string	$clave	Clave del objeto en la colección
	 * @param	string	$valor	Valor del objeto de la colección
	 */
	function put($clave, $valor)
	{
		$this->_saco[$clave] = $valor;
	}
	
	/**
	 * Obtiene un valor en la coleccion
	 *
	 * @access	public
	 * @param	string $clave Clave de la coleccion
	 */
	function get($clave)
	{
		return($this->_saco[$clave]);
	}
	
	
}
?>
