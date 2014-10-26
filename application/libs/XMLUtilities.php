<?php
/**
 * XMLUtilities is a toolbox for handling XML files, dom and sax parsing, convertion between XML and arrays
 * methods are static
 * @author Tristan
 *
 */

/**
 * The XMLUtilities class
 * @author Tristan
 *
 */
class XMLUtilities
{	
	/**
	 * convert an XML file into an array
	 * @param string $xmlfile the xml file to load
	 * @param string $charset the charset of the xmlfile like iso-8859-1 or UTF-8 (if not provided in the xml header)
	 * @return array
	 */
	public static function xmlfile_to_array($xmlfile,$charset="UTF-8")
	{
		
		$docxml = simplexml_load_file($xmlfile);
		$json_string = json_encode($docxml);
		$result_array = json_decode($json_string, TRUE);
		return $result_array;  
	}
	
	/**
	 * convert an XML file into an associative array by trying to match a given element_path
	 * 
	 * @param string $xmlfile the xml file to load
	 * @param string $charset the charset of the xmlfile (if not provided in the xml header)
	 * @param string $element_path the path of elements to match before associating data
	 * @param string $key_attribute the attribute name used as key in the associative array
	 * @param string $value_attribute the attribute name used as value in the associative array (leave empty to use PCDATA instead of an attribute)
	 * @return array
	 */
	public static function xmlfile_to_associative_array($xmlfile,$charset="UTF-8",$element_path="",$key_attribute,$value_attribute="")
	{
		$docxml = new DOMDocument();
		try {
			$docxml->load($xmlfile,LIBXML_DTDLOAD); //to avoid the "&nbsp;" warning, use this LIBXML_DTDLOAD parameter
		}
		catch (Exception $e)
		{
			throw new Exception("file $xmlfile couldn't be opened! ".$e->getMessage());
		}
		if (isset($docxml->xmlEncoding)) $charset=$docxml->xmlEncoding;
		return XMLUtilities::domnode_to_associative_array($docxml->documentElement,$charset,$element_path,$key_attribute,$value_attribute);
	}
	

	/**
	 * convert a dom node into an associative array by trying to match a given element_path
	 * @param DOM element $node
	 * @param string $charset	
	 * @param string $element_path the path of elements to match before associating data
	 * @param string $key_attribute the attribute name used as key in the associative array
	 * @param string $value_attribute the attribute name used as value in the associative array (leave empty to use PCDATA instead of an attribute)
	 * @param string $current_element_path element path built during recursion (leave blank at first call)
	 * @return array
	 */
	public static function domnode_to_associative_array($node,$charset="UTF-8",$element_path="",$key_attribute,$value_attribute="",$current_element_path="")
	{
		$output = array();
		$match=false;
		if (isset($node->tagName))
		{
			if ($current_element_path=="") $current_element_path=strtolower($node->tagName);
			else $current_element_path.="/".strtolower($node->tagName);
			if (strtolower($element_path)==$current_element_path)
				$match=true;
		}
		switch ($node->nodeType)
		{
			case XML_ELEMENT_NODE:
				if ($match)
				{
					//find the key attribute
					$key="";
					if($node->attributes->length)
					{
						foreach($node->attributes as $attrName => $attrNode)
						{
							if (strtolower($attrName)==strtolower($key_attribute))
							{
								$key=$attrNode->value;
								break;
							}
						}
					}
					if ($key=="") return $output;
					//find the value attribute or CDATA
					$value="";
					if($value_attribute!="" && $node->attributes->length)
					{
						foreach($node->attributes as $attrName => $attrNode)
						{
							if (strtolower($attrName)==strtolower($value_attribute))
							{
								$value=htmlspecialchars(trim($attrNode->value),ENT_NOQUOTES);
								break;
							}
						}
					}
					else 
					{
						for ($i=0, $m=$node->childNodes->length; $i<$m; $i++)
						{
							$child = $node->childNodes->item($i);
							if ($child->nodeType==XML_CDATA_SECTION_NODE || $child->nodeType==XML_TEXT_NODE)
								$value.=htmlspecialchars_decode(trim($child->textContent));
						}
					}
					$output[$key]=$value;
					return $output;	
				} //end if match
				else 
				{
					for ($i=0, $m=$node->childNodes->length; $i<$m; $i++)
					{
						$child = $node->childNodes->item($i);
						$v = XMLUtilities::domnode_to_associative_array($child,$charset,$element_path,$key_attribute,$value_attribute,$current_element_path);
						if (is_array($v))
							foreach ($v as $key => $value)
								$output[$key]=$value;
					}
					return $output;
				}//end else (if match)
		 break;
		}
		return $output;
	}
}
?>