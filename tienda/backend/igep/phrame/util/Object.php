<?php
/**
 * Class Object is the root of the class hierarchy. Every class has Object as a
 * superclass. All objects, including arrays, implement the methods of this
 * class.
 *
 * @author	Arnold Cano
 * @version	$Id: Object.php,v 1.2 2007-05-28 10:36:34 afelixf Exp $
 */
class Object
{
	/**
	 * Compares the specified object with this object for equality.
	 *
	 * @access	public
	 * @param	mixed	$object
	 * @return	boolean
	 */
	function equals($object)
	{
		return ($this === $object);
	}
	/**
	 * Returns a string representation of this object.
	 *
	 * @access	public
	 * @return	string
	 */
	function toString()
	{
		return var_export($this, TRUE);
	}
}
?>
