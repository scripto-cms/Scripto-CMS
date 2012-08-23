<?php
class xmlParser {

var $att;
var $id;
var $title;
var $content = array();
var $index=0;
var $xml_parser;
var $tagname;
var $max_news = 0;
var $tag_open = false;
var $rss_charset = '';
var $rss_option = '';

	function xmlParser($file, $max)
	{
		$this->max_news = $max;

		$this->xml_parser = xml_parser_create();
		xml_set_object($this->xml_parser, $this);
		xml_set_element_handler($this->xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($this->xml_parser, 'elementContent');
		$this->rss_option = xml_parser_get_option ( $this->xml_parser, XML_OPTION_TARGET_ENCODING );

		if (!($data = $this->_get_contents($file))) {
			////msg("info", "Fatal Error", "could not open XML input ($file)", "$PHP_SELF?mod=rss");
		}

		preg_replace( "#encoding=\"(.+?)\"#ie", "\$this->get_charset('\\1')", $data );

			if (!xml_parse($this->xml_parser, $data)) {
			
			}

		xml_parser_free($this->xml_parser);
	}

	function _get_contents($file) {

 	  if (function_exists('curl_init')) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $file);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);

		if ($data) return $data; else return false;
	 } 
	 else
	 {

		$data = @file_get_contents($file);

		if ($data) return $data; else return false;

	 }

	}

	function pre_parse($date) {

		global $config;

		$i = 0;

		foreach ($this->content as $content) 
        {
			if ($date) 
			{
				$this->content[$i]['date'] = time()+ ($config['date_adjust']*60);
			}
			else
			{
				$this->content[$i]['date'] = strtotime($content['date']);
			}

			$this->content[$i]['description'] = rtrim($this->content[$i]['description']);
			$this->content[$i]['content'] = rtrim($this->content[$i]['content']);

			if ($this->content[$i]['content'] != '') { $this->content[$i]['description'] = $this->content[$i]['content']; unset ($this->content[$i]['content']); }

			if ( preg_match_all ( "#<div id=\'news-id-(.+?)\'>#si", $this->content[$i]['description'], $out) )
			{

			$this->content[$i]['description'] = preg_replace( "#<div id=\'news-id-(.+?)\'>#si"  , ""  , $this->content[$i]['description'] );
			$this->content[$i]['description'] = substr($this->content[$i]['description'], 0, -6);

			}

			$i++;
        }

	}

	function startElement($parser, $name, $attrs) {

		if ($name=="ITEM")
		   {
				$this->tag_open = true;
		   }

	   			$this->tagname=$name;
	}

	function endElement($parser, $name){

		if ($name=="ITEM")
		   {
			$this->index++;
			$this->tag_open = false;
		   }
	}

	function elementContent($parser, $data) {

	$grab=false;
	if ($this->max_news=='all') {
		$grab=true;
	} else {
	if ($this->index < $this->max_news) {
		$grab=true;
	}
	}
	if ($this->tag_open AND $grab) {

		switch ($this->tagname)
		{
		    case 'TITLE' :
                 @$this->content[$this->index]['title'] .= $data;
                 break;
	     	case 'DESCRIPTION' :
                 @$this->content[$this->index]['description'] .= $data;
                 break;
	     	case 'CONTENT:ENCODED' :
                 @$this->content[$this->index]['content'] .= $data;
                 break;
	     	case 'LINK' :
                 @$this->content[$this->index]['link'] .= $data;
                 break;
	     	case 'PUBDATE' :
                 @$this->content[$this->index]['date'] .= $data;
                 break;
                     
		}
 	 }

   }

	function get_charset ( $charset ){

	if ($this->rss_charset == '' ) $this->rss_charset = strtolower ($charset);

	}

	function convert ( $from, $to ) {

	if ($from == '') return;

     if (function_exists('iconv')) {
		$i = 0;

		foreach ($this->content as $content) 
        {

		if (@iconv($from, $to."//IGNORE", $this->content[$i]['title']))
		$this->content[$i]['title'] = @iconv($from, $to."//IGNORE", $this->content[$i]['title']);

		if (@iconv($from, $to."//IGNORE", $this->content[$i]['description']))
		$this->content[$i]['description'] = @iconv($from, $to."//IGNORE", $this->content[$i]['description']);

		if (@$this->content[$i]['content'] AND @iconv($from, $to."//IGNORE", $this->content[$i]['content']))
		$this->content[$i]['content'] = @iconv($from, $to."//IGNORE", $this->content[$i]['content']);

		$i++;

		}
      }
	}
}

?>