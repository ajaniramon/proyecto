<?php
/**
 * ArrayList of objects that can be administered and searched, while hiding the
 * internal implementation. This is an implementation of the ArrayList class in
 * the Java language.
 *
 * @author	Arnold Cano
 * @version	$Id: ArrayList.php,v 1.1.1.1 2004-06-16 12:25:44 cvs Exp $
 */
class ArrayList extends Object
{
	/**
	 * @var	array
	 */
	var $_elements = array();

	/**
	 * Create an ArrayList with the specified elements.
	 *
	 * @access	public
	 * @param	string	$elements
	 */
	function ArrayList($elements = array())
	{
		if (!empty($elements)) {
			$this->_elements = $elements;
		}
	}
	/**
	 * Get a ListIterator for the ArrayList.
	 *
	 * @access	public
	 * @return	ListIterator
	 */
	function listIterator()
	{
		return new ListIterator($this->_elements);
	}
	/**
	 * Appends the specified element to the end of this list.
	 *
	 * @access	public
	 * @param	mixed	$element
	 * @return	boolean
	 */
	function add($element)
	{
		return (array_push($this->_elements, $element)) ? TRUE : FALSE;
	}
	/**
	 * Appends all of the elements in the specified ArrayList to the end of
	 * this list, in the order that they are returned by the specified
	 * ArrayList's ListIterator.
	 *
	 * @access	public
	 * @param	ArrayList	$list
	 * @return	boolean
	 */
	function addAll($list)
	{
		$before = $this->size();
		if (is_a($list, get_class($this))) {
			$iterator = $list->listIterator();
			while ($iterator->hasNext()) {
				$this->add($iterator->next());
			}
		}
		$after = $this->size();
		return ($before < $after);
	}
	/**
	 * Removes all of the elements from this list.
	 *
	 * @access	public
	 */
	function clear()
	{
		$this->_elements = array();
	}
	/**
	 * Returns true if this list contains the specified element.
	 *
	 * @access	public
	 * @param	mixed	$element
	 * @return	boolean
	 */
	function contains($element)
	{
		return (array_search($element, $this->_elements)) ? TRUE : FALSE;
	}
	/**
	 * Returns the element at the specified position in this list.
	 *
	 * @access	public
	 * @param	integer	$index
	 * @return	mixed
	 */
	function get($index)
	{
		return $this->_elements[$index];
	}
	/**
	 * Searches for the first occurence of the given argument.
	 *
	 * @access	public
	 * @param	mixed	$element
	 * @return	mixed
	 */
	function indexOf($element)
	{
		return array_search($element, $this->_elements);
	}
	/**
	 * Tests if this list has no elements.
	 *
	 * @access	public
	 * @return	boolean
	 */
	function isEmpty()
	{
		return empty($this->_values);
	}
	/**
	 * Returns the index of the last occurrence of the specified object in this
	 * list.
	 *
	 * @access	public
	 * @param	mixed	$element
	 * @return	mixed
	 */
	function lastIndexOf($element)
	{
		for ($i = (count($this->_elements) - 1); $i > 0; $i--) {
			if ($element == $this->get($i)) { return $i; }
		}
	}
	/**
	 * Removes the element at the specified position in this list.
	 *
	 * @access	public
	 * @param	integer	$index
	 * @return	mixed
	 */
	function remove($index)
	{
		$element = $this->get($index);
		if (!is_null($element)) { array_splice($this->_elements, $index, 1); }
		return $element;
	}
	/**
	 * Removes from this List all of the elements whose index is between start,
	 * inclusive and end, exclusive.
	 *
	 * @access	public
	 * @param	integer	$start
	 * @param	integer	$end
	 */
	function removeRange($start, $end)
	{
		array_splice($this->_elements, $start, $end);
	}
	/**
	 * Replaces the element at the specified position in this list with the
	 * specified element.
	 *
	 * @access	public
	 * @param	integer	$index
	 * @param	mixed	$element
	 * @return	mixed
	 */
	function set($index, $element)
	{
		$previous = $this->get($index);
		$this->_elements[$index] = $element;
		return $previous;
	}
	/**
	 * Returns the number of elements in this list.
	 *
	 * @access	public
	 * @return	integer
	 */
	function size()
	{
		return count($this->_elements);
	}
	/**
	 * Returns an array containing all of the elements in this list in the
	 * correct order.
	 *
	 * @access	public
	 * @return	array
	 */
	function toArray()
	{
		return $this->_elements;
	}
}
?>
