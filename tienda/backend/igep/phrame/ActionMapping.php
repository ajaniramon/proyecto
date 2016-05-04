<?php
/**
 * The ActionMapping class represents the information that the ActionController
 * knows about the ActionMapping of a particular request to an instance of a
 * particular Action class. The ActionMapping is passed to the perform() method
 * of the Action class itself, enabling access to this information directly.
 *
 * An ActionMapping has the following minimal set of properties. Additional
 * properties can be provided as needed by subclasses.
 * <ul>
 * <li><b>type</b> - Class name of the Action class used by this ActionMapping.
 * </li>
 * <li><b>name</b> - Name of the ActionForm class, if any, associated with this
 * Action.</li>
 * <li><b>input</b> - Path of the input form to which control should be
 * returned if a validation error is encountered.</li>
 * <li><b>validate</b> - Set to 1 if the validate() method of the ActionForm
 * class (if any) associated with this ActionMapping should be called. [0]</li>
 * </ul>
 *
 * @author	Arnold Cano
 * @version	$Id: ActionMapping.php,v 1.3 2007-06-20 11:01:26 afelixf Exp $
 */
class ActionMapping extends HashMap
{
	/**
	 * @var	string
	 */
	var $_type;
	/**
	 * @var	string
	 */
	var $_name;
	/**
	 * @var	string
	 */
	var $_input;

	/**
	 * Create a ActionMapping with the specified values.
	 *
	 * @access	public
	 * @param	string	$name
	 * @param	array	$mapping
	 */
	function ActionMapping($name, $mapping)
	{
		$this->setType($mapping[_TYPE]);
		$this->setName($name);
		$this->setInput($mapping[_INPUT]);
		if (is_array($mapping[_ACTION_FORWARDS])) {
			$this->_initActionForwards($mapping[_ACTION_FORWARDS]);
		}
	}
	/**
	 * Initialize the ActionForwards array associated with this
	 * ActionMapping.
	 *
	 * @access	private
	 * @param	array	$forwards
	 */
	function _initActionForwards($forwards)
	{
		foreach ($forwards as $name => $forward) {
			$actionForward = new ActionForward($name, $forward);
			$this->put($name, $actionForward);
		}
	}
	/**
	 * Get the type of the ActionForward.
	 *
	 * @access	public
	 * @return	string
	 */
	function getType()
	{
		return $this->_type;
	}
	/**
	 * Set the type of the ActionForward.
	 *
	 * @access	public
	 * @param	string	$type
	 */
	function setType($type)
	{
		$this->_type = $type;
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
	 * Get the input URI of the ActionForward.
	 *
	 * @access	public
	 * @return	string
	 */
	function getInput()
	{
		return $this->_input;
	}
	/**
	 * Set the input URI of the ActionForward.
	 *
	 * @access	public
	 * @param	string	$input
	 */
	function setInput($input)
	{
		$this->_input = $input;
	}
	
	
	function get($key)
	{
		if ($this->containsKey($key)) { return $this->_values[$key]; }
		else
			die('Error: actionForward '.$key.' no existe. Consulte el fichero de mapeos.');
	}
	
	
}
?>
