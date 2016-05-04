<?php
/**
 * The Xml utility class provides methods to generate and transform Xml.
 *
 * @author	Arnold Cano
 * @version	$Id: Xml.php,v 1.1.1.1 2004-06-16 12:25:44 cvs Exp $
 */
class Xml
{
	/**
	 * Generates Xml data based on variable introspection.
	 *
	 * @access	public
	 * @param	mixed	$var
	 * @param	integer	$level
	 * @param	string	$name
	 * @param	boolean	$child
	 * @return	boolean
	 */
	function marshal($var = NULL, $level = 0, $name = NULL, $child = false)
	{
		//automatically process if subclassed
		if (is_null($var)) { $xml = $this->marshal($this); }
		//build tags
		if (is_null($name)) {
			$open = $close = Xml::_processTags($var);
		} else if ($child) {
			$open = "value key='$name'";
			$tags = Xml::_processTags($var);
			$close = 'value';
		} else {
			$open = $close = $name;
		}
		//build xml
		if (is_object($var)) {
			$vars = get_object_vars($var);
			$xml.= Xml::_tab($level)."<$open>\n";
			if ($child) { $xml.= Xml::_tab($level + 1)."<$tags>\n"; }
			//process each var in object
			$xml.= Xml::_processVar($var, $level, $child);
			if ($child) { $xml.= Xml::_tab($level + 1)."</$tags>\n"; }
			$xml.= Xml::_tab($level)."</$close>\n";
		} else if (is_array($var)) {
			$xml.= Xml::_tab($level)."<$open>\n";
			if ($child) { $xml.= Xml::_tab($level + 1)."<$tags>\n"; }
			//process each var in array
			$xml.= Xml::_processVar($var, $level, $child);
			if ($child) { $xml.= Xml::_tab($level + 1)."</$tags>\n"; }
			$xml.= Xml::_tab($level)."</$close>\n";
		} else if (is_string($var)) {
			$xml.= Xml::_tab($level);
			$xml.= "<$open>".Xml::xmlentities($var)."</$close>\n";
		} else if (is_bool($var)) {
			$xml.= Xml::_tab($level);
			$xml.= "<$open>".(($var) ? 'true' : 'false')."</$close>\n";
		} else if (is_numeric($var)) {
			$xml.= Xml::_tab($level)."<$open>".strval($var)."</$close>\n";
		}
		return $xml;
	}
	/**
	 * Utility method used to process the var and return the correct tags.
	 *
	 * @access	private
	 * @param	mixed	$var
	 * @return	string
	 */
	function _processTags($var)
	{
		if (is_object($var)) {
			$tags = get_class($var);
		} else if (is_array($var)) {
			$tags = 'array';
		} else {
			$tags = 'value';
		}
		return $tags;
	}
	/**
	 * Utility method used to process the var and return the correct Xml.
	 *
	 * @access	private
	 * @param	mixed	$var
	 * @param	integer	$level
	 * @param	boolean	$child
	 * @return	string
	 */
	function _processVar($var, $level, $child)
	{
		foreach ($var as $key => $value) {
			if (!is_null($value)) {
				$tabs = ($child) ? $level + 2 : $level + 1;
				$xml.= Xml::marshal($value, $tabs, $key, (is_array($var)));
			}
		}
		return $xml;
	}
	/**
	 * Utility method used to tab Xml data to a specified level.
	 *
	 * @access	private
	 * @param	integer	$level
	 * @return	string
	 */
	function _tab($level)
	{
		$tabs = NULL;
		for ($i = 0; $i < $level; $i++) {
			$tabs.= "\t";
		}
		return $tabs;
	}
	/**
	 * Converts all applicable characters to xml entities. This is similar to
	 * the htmlentities() php function.
	 *
	 * @access	public
	 * @param	string	$xml
	 * @param	boolean	$utf8
	 * @return	string
	 */
	function xmlentities($xml, $utf8 = false)
	{
		//http://www.w3.org/TR/1998/REC-xml-19980210#sec-predefined-ent
		$entities = array(
			'<' 	=> '&lt;',
			'>' 	=> '&gt;',
			'`' 	=> '&apos;',
			'\"'	=> '&quot;',
			'&' 	=> '&amp;',
		);
		if (is_string($xml)) {
			if ($utf8) { $xml = utf8_encode($xml); }
			$xml = strtr($xml, $entities);
		}
		return $xml;
	}
	/**
	 * Transform a document from the supplied Xml data and Xsl stylesheet array
	 * (pipelining) optionally processing any runtime parameters to the
	 * transformer.
	 *
	 * @access	public
	 * @param	string	$xml
	 * @param	array	$xsls
	 * @param	array	$params
	 * @return	string
	 */
	function transform($xml, $xsls, $params = array())
	{
		//automatically process if subclassed
		if (is_null($xml)) { $xml = $this->marshal($this); }
		$xslt = xslt_create();
		//process each stylesheet in pipeline
		foreach ($xsls as $xsl) {
			//utilize previously processed Xml if available
			if (!is_null($result)) { $xml = $result; }
			//force the processor to accept an Xml string instead of a file
			$result = xslt_process($xslt, 'arg:/_xml', $xsl, NULL,
				array('/_xml' => $xml), $params);
		}
		xslt_free($xslt);
		return $result;
	}
}
?>
