<?php
/**
 * Stack of LIFO objects that can be administered and searched, while hiding
 * the internal implementation. This is an implementation of the Stack class in
 * the Java language.
 *
 * @author	Arnold Cano
 * @version	$Id: Stack.php,v 1.1.1.1 2004-06-16 12:25:44 cvs Exp $
 */
class Stack extends ArrayList
{
	/**
	 * Create a Stack with the specified values.
	 *
	 * @access	public
	 * @param	string	$values
	 */
	function Stack($values = array())
	{
		ArrayList::ArrayList($values);
	}
	/**
	 * Peek at the value from the top of the Stack without removing it.
	 *
	 * @access	public
	 * @return	mixed
	 */
	function peek()
	{
		return reset($this->toArray());
	}
	/**
	 * Pop a value from the Stack.
	 *
	 * @access	public
	 * @return	mixed
	 */
	function pop()
	{
		return array_pop($this->toArray());
	}
	/**
	 * Push a value into the Stack.
	 *
	 * @access	public
	 * @param	mixed	$value
	 * @return	mixed
	 */
	function push($value)
	{
		$this->add($value);
		return $value;
	}
}
?>
