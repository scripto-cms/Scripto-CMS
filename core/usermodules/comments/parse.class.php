<?php
class ParseFilter {
	var $tagsArray;
	var $attrArray;
	var $tagsMethod;
	var $attrMethod;
	var $xssAuto;
	var $code_text = array ();
	var $code_count = 0;
	var $wysiwyg = false;
	var $allow_php = false;
	var $safe_mode = false;
	var $leech_mode = false;
	var $tagBlacklist = array('applet', 'body', 'bgsound', 'base', 'basefont', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'script', 'style', 'title', 'xml');
	var $attrBlacklist = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');
	var $smilies_arr=array();
	var $config;
	
	function ParseFilter($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1) {		
		for ($i = 0; $i < count($tagsArray); $i++) $tagsArray[$i] = strtolower($tagsArray[$i]);
		for ($i = 0; $i < count($attrArray); $i++) $attrArray[$i] = strtolower($attrArray[$i]);
		$this->tagsArray = (array) $tagsArray;
		$this->attrArray = (array) $attrArray;
		$this->tagsMethod = $tagsMethod;
		$this->attrMethod = $attrMethod;
		$this->xssAuto = $xssAuto;
	}
	function process($source) {
		if (is_array($source)) {
			foreach($source as $key => $value)
				if (is_string($value)) $source[$key] = $this->remove($this->decode($value));
			return $source;
		} elseif (is_string($source)) {

			$source = $this->remove($this->decode($source));

			if($this->code_count) {
				foreach ($this->code_text as $key_find => $key_replace) {
					$find[] = $key_find;
					$replace[] = $key_replace;
				}

			  $source = str_replace($find, $replace, $source);
			}

		  $this->code_count = 0;
		  $this->code_text = array();

		  return $source;
		} else return $source;	
	}
	
	function remove($source) {
		$loopCounter=0;
		while($source != $this->filterTags($source)) {
			$source = $this->filterTags($source);
			$loopCounter++;
		}
		return $source;
	}	
	function filterTags($source) {
		$preTag = NULL;
		$postTag = $source;
		$tagOpen_start = strpos($source, '<');
		while($tagOpen_start !== FALSE) {
			$preTag .= substr($postTag, 0, $tagOpen_start);
			$postTag = substr($postTag, $tagOpen_start);
			$fromTagOpen = substr($postTag, 1);
			$tagOpen_end = strpos($fromTagOpen, '>');
			if ($tagOpen_end === false) break;
			$tagOpen_nested = strpos($fromTagOpen, '<');
			if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
				$preTag .= substr($postTag, 0, ($tagOpen_nested+1));
				$postTag = substr($postTag, ($tagOpen_nested+1));
				$tagOpen_start = strpos($postTag, '<');
				continue;
			} 
			$tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
			$currentTag = substr($fromTagOpen, 0, $tagOpen_end);
			$tagLength = strlen($currentTag);
			if (!$tagOpen_end) {
				$preTag .= $postTag;
				$tagOpen_start = strpos($postTag, '<');			
			}
			$tagLeft = $currentTag;
			$attrSet = array();
			$currentSpace = strpos($tagLeft, ' ');
			if (substr($currentTag, 0, 1) == "/") {
				$isCloseTag = TRUE;
				list($tagName) = explode(' ', $currentTag);
				$tagName = substr($tagName, 1);
			} else {
				$isCloseTag = FALSE;
				list($tagName) = explode(' ', $currentTag);
			}		
			if ((!preg_match("/^[a-z][a-z0-9]*$/i",$tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto))) { 				
				$postTag = substr($postTag, ($tagLength + 2));
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}
			while ($currentSpace !== FALSE) {
				$fromSpace = substr($tagLeft, ($currentSpace+1));
				$nextSpace = strpos($fromSpace, ' ');
				$openQuotes = strpos($fromSpace, '"');
				$closeQuotes = strpos(substr($fromSpace, ($openQuotes+1)), '"') + $openQuotes + 1;
				if (strpos($fromSpace, '=') !== FALSE) {
					if (($openQuotes !== FALSE) && (strpos(substr($fromSpace, ($openQuotes+1)), '"') !== FALSE))
						$attr = substr($fromSpace, 0, ($closeQuotes+1));
					else $attr = substr($fromSpace, 0, $nextSpace);
				} else $attr = substr($fromSpace, 0, $nextSpace);
				if (!$attr) $attr = $fromSpace;
				$attrSet[] = $attr;
				$tagLeft = substr($fromSpace, strlen($attr));
				$currentSpace = strpos($tagLeft, ' ');
			}
			$tagFound = in_array(strtolower($tagName), $this->tagsArray);			
			if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) {
				if (!$isCloseTag) {
					$attrSet = $this->filterAttr($attrSet);
					$preTag .= '<' . $tagName;
					for ($i = 0; $i < count($attrSet); $i++)
						$preTag .= ' ' . $attrSet[$i];
					if (strpos($fromTagOpen, "</" . $tagName)) $preTag .= '>';
					else $preTag .= ' />';
			    } else $preTag .= '</' . $tagName . '>';
			}
			$postTag = substr($postTag, ($tagLength + 2));
			$tagOpen_start = strpos($postTag, '<');			
		}
		$preTag .= $postTag;
		return $preTag;
	}

	function filterAttr($attrSet) {	
		$newSet = array();
		for ($i = 0; $i < count($attrSet); $i++) {
			if (!$attrSet[$i]) continue;

           $attrSet[$i] = trim($attrSet[$i]);

 		   $exp = strpos($attrSet[$i], '=');
		   if ($exp === false) $attrSubSet = Array($attrSet[$i]); else {
		   $attrSubSet = Array();
           $attrSubSet[] = substr($attrSet[$i], 0, $exp);
           $attrSubSet[] = substr($attrSet[$i], $exp + 1);}
		   $attrSubSet[1] = stripslashes($attrSubSet[1]);


			list($attrSubSet[0]) = explode(' ', $attrSubSet[0]);
			
			if ((!eregi("^[a-z]*$",$attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist)) || (substr($attrSubSet[0], 0, 2) == 'on')))) 
				continue;
			if ($attrSubSet[1]) {
				$attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
				$attrSubSet[1] = preg_replace('/\s+/', ' ', $attrSubSet[1]);
				$attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
				if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'"))
					$attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
			}

			if (	((strpos(strtolower($attrSubSet[1]), 'expression') !== false) &&	(strtolower($attrSubSet[0]) == 'style')) ||
					(strpos(strtolower($attrSubSet[1]), 'javascript:') !== false) ||
					(strpos(strtolower($attrSubSet[1]), 'behaviour:') !== false) ||
					(strpos(strtolower($attrSubSet[1]), 'vbscript:') !== false) ||
					(strpos(strtolower($attrSubSet[1]), 'mocha:') !== false) ||
					(strpos(strtolower($attrSubSet[1]), 'data:') !== false AND strtolower($attrSubSet[0]) == "href") ||
					(strpos(strtolower($attrSubSet[1]), 'livescript:') !== false) 
			) continue;

			$attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);
			if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod)) {
				if ($attrSubSet[1]) $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
				elseif ($attrSubSet[1] == "0") $newSet[] = $attrSubSet[0] . '="0"';
				else $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[0] . '"';
			}	
		};
		return $newSet;
	}
	function decode($source) {

		$source = preg_replace( "#\[code\](.+?)\[/code\]#ies", "\$this->code_tag( '\\1' )", $source );

		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);

		$source = strtr($source, $trans_tbl);

	    $source = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $source);

		if ($this->safe_mode AND !$this->wysiwyg) {

			$source = htmlspecialchars($source, ENT_QUOTES);
			$source = str_replace('&amp;', '&', $source );


		} else {

		$source = str_replace("<>", "&lt;&gt;", str_replace(">>", "&gt;&gt;", str_replace("<<", "&lt;&lt;", $source ) ) );
		$source = str_replace("<!--", "&lt;!--", $source );

		}

		return $source;
	}

	function BB_Parse($source, $use_html=TRUE) {

		$config=$this->config;
		if ($use_html==false) {
		$source=strip_tags($source);
		}
        $find= array(
					'/javascript:/si',
					'/about:/si',
					'/vbscript:/si',
					"'\[quote\]'si",
					"'\[quote=(.+?)\]'si",
					"'\[/quote\]'si",
                    );

        $replace=array(
                      "javascript<b></b>:",
                      "about<b></b>:",
                      "vbscript<b></b>:",
					  "<!--QuoteBegin--><div class=\"quote\"><!--QuoteEBegin-->",
					  "<!--QuoteBegin \\1 --><div class=\"title_quote\"> \\1</div><div class=\"quote\"><!--QuoteEBegin-->",
					  "<!--QuoteEnd--></div><!--QuoteEEnd-->",
                      );

		
			$source = str_replace("\r", "", $source ); 
			$source = str_replace("\n", "<br />", $source ); 			
		
		$smilies_arr=$this->smilies_arr;
		if (is_Array($smilies_arr)) {
        foreach($smilies_arr as $smile){;
            $smile = trim($smile);
            $find[] = "':$smile:'";
            $replace[] = "<img style=\"border: none;\" alt=\"$smile\" align=\"absmiddle\" src=\"".$config['imgpath']."images/emoticons/$smile.gif\" />";
		}
		}

    $source = preg_replace($find,$replace,$source);

	$source = str_replace("`", "&#96;", $source ); 

    if (!$this->allow_php) {

		$source = str_replace("<?", "&lt;?", $source );
		$source = str_replace("?>", "?&gt;", $source );

	}

	$source = preg_replace( "#\[code\](.+?)\[/code\]#is", "<!--code1--><div class=\"scriptcode\"><!--ecode1-->\\1<!--code2--></div><!--ecode2-->", $source );
	$source = preg_replace( "#\[(left|right|center)\](.+?)\[/\\1\]#is"  , "<div align=\"\\1\">\\2</div>", $source );

	$source = preg_replace( "#\[b\](.+?)\[/b\]#is", "<b>\\1</b>", $source );
	$source = preg_replace( "#\[i\](.+?)\[/i\]#is", "<i>\\1</i>", $source );
	$source = preg_replace( "#\[u\](.+?)\[/u\]#is", "<u>\\1</u>", $source );
	$source = preg_replace( "#\[s\](.+?)\[/s\]#is", "<s>\\1</s>", $source );

	$source = preg_replace( "#\[url\](\S.+?)\[/url\]#ie"                                       , "\$this->build_url(array('html' => '\\1', 'show' => '\\1'))", $source );
	$source = preg_replace( "#\[url\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/url\]#ie" , "\$this->build_url(array('html' => '\\1', 'show' => '\\2'))", $source );
	$source = preg_replace( "#\[url\s*=\s*(\S.+?)\s*\](.*?)\[\/url\]#ie"                       , "\$this->build_url(array('html' => '\\1', 'show' => '\\2'))", $source );

	$source = preg_replace( "#\[video\](\S.+?)\[/video\]#ie"                                       , "\$this->build_url_video(array('html' => '\\1', 'show' => '\\1'))", $source );
	$source = preg_replace( "#\[video\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/video\]#ie" , "\$this->build_url_video(array('html' => '\\1', 'show' => '\\2'))", $source );
	$source = preg_replace( "#\[video\s*=\s*(\S.+?)\s*\](.*?)\[\/video\]#ie"                       , "\$this->build_url_video(array('html' => '\\1', 'show' => '\\2'))", $source );

	$source = preg_replace( "#\[leech\](\S.+?)\[/leech\]#ie"                                       , "\$this->build_url(array('html' => '\\1', 'show' => '\\1', 'leech' => '1'))", $source );
	$source = preg_replace( "#\[leech\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/leech\]#ie" , "\$this->build_url(array('html' => '\\1', 'show' => '\\2', 'leech' => '1'))", $source );
	$source = preg_replace( "#\[leech\s*=\s*(\S.+?)\s*\](.*?)\[\/leech\]#ie"                       , "\$this->build_url(array('html' => '\\1', 'show' => '\\2', 'leech' => '1'))", $source );


	$source = preg_replace( "#\[email\](\S+?)\[/email\]#i"                                                                , "<a href=\"mailto:\\1\">\\1</a>", $source );
	$source = preg_replace( "#\[email\s*=\s*\&quot\;([\.\w\-]+\@[\.\w\-]+\.[\.\w\-]+)\s*\&quot\;\s*\](.*?)\[\/email\]#i"  , "<a href=\"mailto:\\1\">\\2</a>", $source );
	$source = preg_replace( "#\[email\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/email\]#i"                       , "<a href=\"mailto:\\1\">\\2</a>", $source );

	if (!$this->safe_mode) {
	$source = preg_replace( "#\[img\](.+?)\[/img\]#ie", "\$this->build_image('\\1')", $source );
	$source = preg_replace( "#\[img=(.+?)\](.+?)\[/img\]#ie", "\$this->build_image('\\2', '\\1')", $source );
	$source = preg_replace( "'\[thumb\]([^\[]*)([/\\\\])(.*?)\[/thumb\]'ie", "\$this->build_thumb('\$1\$2\$3', '\$1\$2thumbs\$2\$3')", $source );
	$source = preg_replace( "'\[thumb=(.*?)\]([^\[]*)([/\\\\])(.*?)\[/thumb\]'ie", "\$this->build_thumb('\$2\$3\$4', '\$2\$3thumbs\$3\$4', '\$1')", $source );
	$source = preg_replace( "#\[video\s*=\s*(\S.+?)\s*\]#ie", "\$this->build_video('\\1')", $source );
	}

	while( preg_match( "#\[color=([^\]]+)\](.+?)\[/color\]#ies", $source ) )
	{
		$source = preg_replace( "#\[color=([^\]]+)\](.+?)\[/color\]#ies"  , "\$this->color(array('1'=>'\\1','2'=>'\\2'))", $source );
	}

	return trim($source);

	}

function decodeBBCodes($txt, $use_html=TRUE, $wysiwig = "no") {

		$config=$this->config;
		$find = array (); $result = array ();
		$txt=stripslashes($txt);

			$txt = preg_replace( "#<!--ThumbBegin-->(.+?)ShowBild\(\'(.+?)\'\)(.+?)<img align=['\"](.+?)['\"].+?"."<!--ThumbEnd-->#", '[thumb=\\4]\\2[/thumb]', $txt );
			$txt = preg_replace( "#<!--ThumbBegin-->(.+?)ShowBild\(\'(.+?)\'\)(.+?)<!--ThumbEnd-->#", '[thumb]\\2[/thumb]'         , $txt );
			$txt = preg_replace( "#<!--QuoteBegin-->(.+?)<!--QuoteEBegin-->#", '[quote]'       , $txt );
			$txt = preg_replace( "#<!--QuoteBegin ([^>]+?) -->(.+?)<!--QuoteEBegin-->#"        , "[quote=\\1]"     , $txt );
			$txt = preg_replace( "#<!--QuoteEnd-->(.+?)<!--QuoteEEnd-->#"    , '[/quote]'      , $txt );
			$txt = preg_replace( "#<!--code1-->(.+?)<!--ecode1-->#", '[code]' , $txt );
			$txt = preg_replace( "#<!--code2-->(.+?)<!--ecode2-->#", '[/code]', $txt );
			$txt = preg_replace( "#<!--dle_video_begin-->(.+?)src=\"(.+?)\"(.+?)<!--dle_video_end-->#is", '[video=\\2]', $txt );
			$txt = str_replace( '&nbsp;&nbsp;&nbsp;&nbsp;', "\t", $txt );
			$txt = str_replace( '&nbsp;&nbsp;'            , "  ", $txt );


		if ($wysiwig != "yes") {
			$txt = preg_replace( "#<i>(.+?)</i>#is"  , "[i]\\1[/i]"  , $txt );
			$txt = preg_replace( "#<b>(.+?)</b>#is"  , "[b]\\1[/b]"  , $txt );
			$txt = preg_replace( "#<s>(.+?)</s>#is"  , "[s]\\1[/s]"  , $txt );
			$txt = preg_replace( "#<u>(.+?)</u>#is"  , "[u]\\1[/u]"  , $txt );
			$txt = preg_replace( "#<center>(.+?)</center>#is", "[center]\\1[/center]"  , $txt );
			$txt = preg_replace( "#<img src=[\"'](\S+?)['\"] align=['\"](.+?)['\"].+?".">#is", "[img=\\2]\\1[/img]", $txt );
			$txt = preg_replace( "#<img src=[\"'](\S+?)['\"].+?".">#is", "[img]\\1[/img]", $txt );

			$txt = preg_replace( "#<!--dle_leech_begin--><a href=[\"'](http://|https://|ftp://|ed2k://|news://|magnet:)?(\S.+?)['\"].+?".">(.+?)</a><!--dle_leech_end-->#ie" , "\$this->decode_leech('\\1\\2', '\\3')", $txt );
			$txt = preg_replace( "#<a href=[\"']mailto:(.+?)['\"]>(.+?)</a>#", "[email=\\1]\\2[/email]", $txt );
			$txt = preg_replace( "#<a href=[\"'](http://|https://|ftp://|ed2k://|news://|magnet:)?(\S.+?)['\"].+?".">(.+?)</a>#" , "[url=\\1\\2]\\3[/url]"  , $txt );

			while ( preg_match( "#<span style=['\"]color:(.+?)['\"]>(.+?)</span>#is", $txt ) )
			{
				$txt = preg_replace( "#<span style=['\"]color:(.+?)['\"]>(.+?)</span>#is"    , "[color=\\1]\\2[/color]", $txt );
			}
			while ( preg_match( "#<div align=['\"]left['\"]>(.+?)</div>#is", $txt ) )
			{
				$txt = preg_replace( "#<div align=['\"]left['\"]>(.+?)</div>#is"    , "[left]\\1[/left]", $txt );
			}
			while ( preg_match( "#<div align=['\"]right['\"]>(.+?)</div>#is", $txt ) )
			{
				$txt = preg_replace( "#<div align=['\"]right['\"]>(.+?)</div>#is"    , "[right]\\1[/right]", $txt );
			}
			while ( preg_match( "#<div align=['\"]center['\"]>(.+?)</div>#is", $txt ) )
			{
				$txt = preg_replace( "#<div align=['\"]center['\"]>(.+?)</div>#is"    , "[center]\\1[/center]", $txt );
			}

		}

		$smilies_arr=$this->smilies_arr;
 		if (is_Array($smilies_arr)) {
        foreach($smilies_arr as $smile){
            $smile = trim($smile);
            $replace[] = ":$smile:";
            $find[] = "#<img style=['\"]border: none;['\"] alt=['\"]".$smile."['\"] align=['\"]absmiddle['\"] src=['\"](.+?)".$smile.".gif['\"] />#is";
		}
		}

			$txt = preg_replace( $find , $replace, $txt );

		
		$txt = str_replace( "<br>"  , "\n", $txt );
		$txt = str_replace( "<br />", "\n", $txt );
		$txt = str_replace( "<BR>"  , "\n", $txt );
		$txt = str_replace( "<BR />", "\n", $txt );
		

		$txt = htmlspecialchars($txt, ENT_QUOTES);
		if (!$use_html)	$txt = str_replace( "&amp;" , "&"   , $txt);

	return trim($txt);

	}

	function color( $IN )
	{
		$style = $IN['1'];
		$text  = stripslashes($IN['2']);
		
		$style = str_replace( '&quot;', '', $style );
		
		
		$style = preg_replace( "/[&\(\)\.\%\[\]<>\'\"]/", "", preg_replace( "#^(.+?)(?:;|$)#", "\\1", $style ) );
	
		$style = preg_replace( "/[^\d\w\#\s]/s", "", $style );
		return "<span style=\"color:".$style."\">".$text."</span>";
	}

	
	function build_url_video( $url=array() )
	{
		global $config;

		$skip_it = 0;
		
		if ( preg_match( "/([\.,\?]|&#33;)$/", $url['show'], $match) )
		{
			$url['end'] .= $match[1];
			$url['show'] = preg_replace( "/([\.,\?]|&#33;)$/", "", $url['show'] );
		}

		$url['html'] = $this->clear_url( $url['html'] );
		$url['show'] = stripslashes($url['show']);

		
		if ( ! preg_match("#^(http|news|https|ed2k|ftp|aim)://|(magnet:?)#", $url['html'] ) )
		{
			$url['html'] = 'http://'.$url['html'];
		}
		
		if ( preg_match( "/^<img src/i", $url['show'] ) )
		{
			$skip_it     = 1;
		}

		$url['show'] = str_replace( "&amp;amp;" , "&amp;" , $url['show'] );
		$url['show'] = preg_replace( "/javascript:/i", "javascript&#58; ", $url['show'] );
		
		if ( (strlen($url['show']) -58 ) < 3 )  $skip_it = 1;
		
		if ( ! preg_match( "/^(http|ed2k|ftp|https|news):\/\//i", $url['show'] )) $skip_it = 1;
		
		$show = $url['show'];
		
		if ($skip_it != 1)
		{
			$stripped = preg_replace( "#^(http|ed2k|ftp|https|news)://(\S+)$#i", "\\2", $url['show'] );
			$uri_type = preg_replace( "#^(http|ed2k|ftp|https|news)://(\S+)$#i", "\\1", $url['show'] );
			
			$show = $uri_type.'://'.substr( $stripped , 0, 35 ).'...'.substr( $stripped , -15   );
		}

		if ($this->check_home($url['html'])) $target = ""; else $target = "target=\"_blank\"";
	
	$url_tmp=strtolower(str_replace("www.","",$url["html"]));
	
	if (strpos($url_tmp,"http://youtube.com/")!==false) {
		$url["html"]=str_Replace("watch?v=","v/",$url["html"]);
			return "<a href=\"".$url['html']."\" ".$target."  class=\"lightwindow page-options\" params=\"lightwindow_width=425,lightwindow_height=340,lightwindow_loading_animation=false\" title=\"$show\">".$show."</a>";
	} elseif (strpos($url_tmp,"http://rutube.ru/")!==false) {
		$pos=strpos($url_tmp,"v=");
		$id_movie=substr($url_tmp,$pos+2,strlen($url_tmp));
		$url["html"]="http://video.rutube.ru/$id_movie";
			return "<a href=\"".$url['html']."\" ".$target."  class=\"lightwindow page-options\" params=\"lightwindow_width=425,lightwindow_height=340,lightwindow_loading_animation=false\" title=\"$show\">".$show."</a>";
	} else {
			return "<a href=\"".$_SESSION["rroot"]."video/?file=".$url['html']."\" ".$target."  class=\"lightwindow page-options\" params=\"lightwindow_type=external,lightwindow_width=435,lightwindow_height=350,lightwindow_loading_animation=false\" title=\"$show\">".$show."</a>";	
	}


		
	}
	
	function build_url( $url=array() )
	{
		global $config;

		$skip_it = 0;
		
		if ( preg_match( "/([\.,\?]|&#33;)$/", $url['show'], $match) )
		{
			$url['end'] .= $match[1];
			$url['show'] = preg_replace( "/([\.,\?]|&#33;)$/", "", $url['show'] );
		}

		$url['html'] = $this->clear_url( $url['html'] );
		$url['show'] = stripslashes($url['show']);

		
		if ( ! preg_match("#^(http|news|https|ed2k|ftp|aim)://|(magnet:?)#", $url['html'] ) )
		{
			$url['html'] = 'http://'.$url['html'];
		}
		
		if ( preg_match( "/^<img src/i", $url['show'] ) )
		{
			$skip_it     = 1;
		}

		$url['show'] = str_replace( "&amp;amp;" , "&amp;" , $url['show'] );
		$url['show'] = preg_replace( "/javascript:/i", "javascript&#58; ", $url['show'] );
		
		if ( (strlen($url['show']) -58 ) < 3 )  $skip_it = 1;
		
		if ( ! preg_match( "/^(http|ed2k|ftp|https|news):\/\//i", $url['show'] )) $skip_it = 1;
		
		$show = $url['show'];
		
		if ($skip_it != 1)
		{
			$stripped = preg_replace( "#^(http|ed2k|ftp|https|news)://(\S+)$#i", "\\2", $url['show'] );
			$uri_type = preg_replace( "#^(http|ed2k|ftp|https|news)://(\S+)$#i", "\\1", $url['show'] );
			
			$show = $uri_type.'://'.substr( $stripped , 0, 35 ).'...'.substr( $stripped , -15   );
		}

		if ($this->check_home($url['html'])) $target = ""; else $target = "target=\"_blank\"";



			return "<a href=\"".$url['html']."\" ".$target.">".$show."</a>";


		
	}

	function code_tag($txt="")
	{		
		if ( $txt == "" )
		{
			return;
		}

		$this->code_count ++;

		$txt = str_replace( "&" , "&amp;", $txt );
		$txt = str_replace( "&lt;"      , "&#60;" , $txt );
		$txt = str_replace( "'"      	, "&#39;" , $txt );
		$txt = str_replace( "&gt;"      , "&#62;" , $txt );
		$txt = str_replace( "<"         , "&#60;" , $txt );
		$txt = str_replace( ">"         , "&#62;" , $txt );
		$txt = str_replace( "&quot;"    , "&#34;" , $txt );
		$txt = str_replace( "\\\""      , "&#34;" , $txt );
		$txt = str_replace( ":"         , "&#58;" , $txt );
		$txt = str_replace( "["         , "&#91;" , $txt );
		$txt = str_replace( "]"         , "&#93;" , $txt );
		$txt = str_replace( ")"         , "&#41;" , $txt );
		$txt = str_replace( "("         , "&#40;" , $txt );
		$txt = str_replace( "\r"        , "", $txt );
		$txt = str_replace( "\n"        , "<br />", $txt );

		$txt = preg_replace( "#\s{1};#" , "&#59;" , $txt );
		$txt = preg_replace( "#\t#"   , "&nbsp;&nbsp;&nbsp;&nbsp;", $txt );
		$txt = preg_replace( "#\s{2}#", "&nbsp;&nbsp;"            , $txt );

		$p = "[code]{".$this->code_count."}[/code]";
		
		$this->code_text[$p] = "[code]{$txt}[/code]";

		return $p;
	}

	function build_video ( $url )
	{
		$url = $this->clear_url( urldecode( $url ) );

		return "<!--dle_video_begin--><object id=\"mediaPlayer\" width=\"320\" height=\"310\" classid=\"CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6\" standby=\"Loading Microsoft Windows Media Player components...\" type=\"application/x-oleobject\">
				<param name=\"url\" VALUE=\"{$url}\" />
				<param name=\"autoStart\" VALUE=\"false\" />
				<param name=\"showControls\" VALUE=\"true\" />
				<param name=\"TransparentatStart\" VALUE=\"false\" />
				<param name=\"AnimationatStart\" VALUE=\"true\" />
				<param name=\"StretchToFit\" VALUE=\"true\" />
				<embed pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/MediaPlayer/\" src=\"{$url}\" width=\"320\" height=\"310\" type=\"application/x-mplayer2\" autorewind=\"1\" showstatusbar=\"1\" showcontrols=\"1\" autostart=\"0\" allowchangedisplaysize=\"1\" volume=\"70\" stretchtofit=\"1\" />
				</object><!--dle_video_end-->";

	}

	function build_image($url="", $align="")
	{	
		global $config;
	
		$url   = trim($url);
		$url   = urldecode( $url );
		$align = trim($align);
        $img_extra = " ";

		if ($align != "left" AND $align != "right") $align = '';

		$url = $this->clear_url( urldecode( $url ) );

		if (isset($_POST["title"])) {
		$alt = "alt='".htmlspecialchars(strip_tags(stripslashes($_POST['title'])), ENT_QUOTES)."' ";
		} else {
		$alt="";
		}

		if (isset($config['tag_img_width']))
        if (intval($config['tag_img_width'])) {

           $img_info = @getimagesize($url);

            if ($img_info[0] > $config['tag_img_width']) {

                 $out_heigh =($img_info[1] / 100) * ($config['tag_img_width'] / ($img_info[0] / 100));
                 $out_heigh = floor($out_heigh);
				 $img_extra = " width='{$config['tag_img_width']}' height='{$out_heigh}' onmouseover=\"this.style.cursor='pointer';\" onclick=\"window.open('$url'); return false;\" ";

            }

		}

		$img_extra .= $alt;
		if ($align == '')
		return "<img src=\"$url\" style=\"border: none;\"{$img_extra}/>";
		else
		return "<img src=\"$url\" align=\"$align\" style=\"border: none;\"{$img_extra}/>";
	}

	function build_thumb($gurl="", $url="", $align="")
	{		
		$url   = trim($url);
		$url   = $this->clear_url( urldecode( $url ) );

		$gurl  = trim($gurl);
		$gurl  = $this->clear_url( urldecode( $gurl ) );

		$align = trim($align);

		if ($align != "left" AND $align != "right") $align = '';

		$alt = "alt='".htmlspecialchars(strip_tags(stripslashes($_POST['title'])), ENT_QUOTES)."'";

		if ($align == '')
		return "<!--ThumbBegin--><a href=\"#\" onClick=\"ShowBild('$gurl'); return false;\" ><img src=\"$url\" style=\"border: none;\" {$alt} /></a><!--ThumbEnd-->";
		else
		return "<!--ThumbBegin--><a href=\"#\" onClick=\"ShowBild('$gurl'); return false;\" ><img align=\"$align\" src=\"$url\" style=\"border: none;\" {$alt} /></a><!--ThumbEnd-->";
	}

	function clear_url ($url) {

		$url = strip_tags( stripslashes($url) );

		$url = str_replace( '\"', '"', $url );


		if (!$this->safe_mode OR $this->wysiwyg) {

			$url = htmlspecialchars($url, ENT_QUOTES);

		}

		$url = str_replace( "document.cookie", "", $url );
		$url = str_replace( " ", "%20", $url );
		$url = str_replace( "'", "", $url );
		$url = str_replace( '"', "", $url );
		$url = str_replace( "<", "&#60;" , $url );
		$url = str_replace( ">", "&#62;" , $url );
		$url = preg_replace( "/javascript:/i", "", $url );
		$url = preg_replace( "/data:/i", "", $url );

		return $url;

	}

	function decode_leech($url="", $show="")
	{	

		if ($this->leech_mode) return "[url=".$url."]".$show."[/url]";

		$url = end ( explode ("url=", $url) );
		$url = rawurldecode($url);
		$url = base64_decode($url);

		return "[leech=".$url."]".$show."[/leech]";
	}

	function check_home ($url) {
		return false;
	}

}
?>