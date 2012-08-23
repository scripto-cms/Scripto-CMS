<?
//класс для работы с БД
class Db {
var $config;
var $categories;
var $photos;
var $settings;
var $objects;
var $videos;
var $audio;
var $flash;
var $templates;
var $blocks;
var $block_types;
var $block_text;
var $block_rss;
var $block_categories;
var $categories_module;
var $static_modules;
var $reminders;
var $notes;
var $process;
var $buttons;
var $languages;
var $prefixes=array();
var $user_mode=false;
var $internal_number=0;
var $array_language=null;
var $current_prefix='';

function addPrefix($prefix,$real_prefix) {
	$pref=array();
	$pref["prefix"]=$prefix;
	$pref["real_prefix"]=$real_prefix;
	$this->prefixes[]=$pref;
}

function setPrefix() {
	$this->categories=$this->config["db"]["prefix"]."categories";
	$this->photos=$this->config["db"]["prefix"]."photos";
	$this->settings=$this->config["db"]["prefix"]."settings";
	$this->objects=$this->config["db"]["prefix"]."objects";
	$this->videos=$this->config["db"]["prefix"]."videos";
	$this->audio=$this->config["db"]["prefix"]."audio";
	$this->flash=$this->config["db"]["prefix"]."flash";
	$this->templates=$this->config["db"]["prefix"]."templates";
	$this->blocks=$this->config["db"]["prefix"]."blocks";
	$this->block_types=$this->config["db"]["prefix"]."block_types";
	$this->block_text=$this->config["db"]["prefix"]."block_text";
	$this->block_rss=$this->config["db"]["prefix"]."block_rss";
	$this->block_categories=$this->config["db"]["prefix"]."block_categories";
	$this->categories_module=$this->config["db"]["prefix"]."categories_modules";
	$this->static_modules=$this->config["db"]["prefix"]."static_modules";
	$this->reminders=$this->config["db"]["prefix"]."reminders";
	$this->notes=$this->config["db"]["prefix"]."notes";
	$this->process=$this->config["db"]["prefix"]."process";
	$this->buttons=$this->config["db"]["prefix"]."buttons";
	$this->languages=$this->config["db"]["prefix"]."languages";
}

function fetch($result) {
	$r_id=str_replace('Resource id #','',$result);
	$row=@mysql_fetch_assoc($result);
	if (preg_match("/^[0-9]{1,}$/i",$r_id))
	$new_rw=$this->convertArrayToLang($row,$r_id);
	if (isset($new_rw)) {
		return $new_rw;
	} else {
		return false;
	}
}

function convertArrayToLang($row,$r_id=0) {
	if (!isset($r_id)) $r_id=0;
	if ($this->user_mode==true) {
	 if ($this->internal_number==0 && $this->current_prefix!='') {
		if (is_array($row)) {
		 foreach ($row as $k=>$rw) {
		 	$pos=@strpos($k,$this->current_prefix);
				if ($pos!==false) {
					$r=strlen($k)-$pos-strlen($this->current_prefix);
					if ($r==0) {
						$n=str_replace($this->current_prefix,'',$k);
						$this->array_language[$r_id][$n]=$k;
					}
				}
		 }
		}
	 }
	 if (isset($this->array_language[$r_id])) {
	 	if (is_array($this->array_language[$r_id])) {
	 		foreach ($this->array_language[$r_id] as $n=>$k) {
				if (!empty($row[$k])) {
					$row[$n]=$row[$k];
					unset($row[$k]);
				} else {
					if (!empty($row[$n]))
						$row[$n]='-';
				}
			}
		}
	 }
	}
	$this->internal_number++;
	return $row;
}

function query_array($array,$skip_error=false) {
if (is_array($array)) {
	foreach ($array as $arr) {
		if ($skip_error) {
			$this->query($arr);
		} else {
			if (!$this->query($arr)) {
				return false;
			}
		}
	}
	return true;
} else {
return false;
}
}

function Query($query,$print_query=false) {
global $config;
	$this->internal_number=0;
	$query=str_replace("%categories%",$this->categories,$query);
	$query=str_replace("%photos%",$this->photos,$query);
	$query=str_replace("%settings%",$this->settings,$query);
	$query=str_replace("%objects%",$this->objects,$query);
	$query=str_replace("%videos%",$this->videos,$query);
	$query=str_replace("%video%",$this->videos,$query);
	$query=str_replace("%audio%",$this->audio,$query);
	$query=str_replace("%flash%",$this->flash,$query);
	$query=str_replace("%templates%",$this->templates,$query);
	$query=str_replace("%blocks%",$this->blocks,$query);
	$query=str_replace("%block_types%",$this->block_types,$query);
	$query=str_replace("%block_text%",$this->block_text,$query);
	$query=str_replace("%block_rss%",$this->block_rss,$query);
	$query=str_replace("%block_categories%",$this->block_categories,$query);
	$query=str_replace("%categories_module%",$this->categories_module,$query);
	$query=str_replace("%static_modules%",$this->static_modules,$query);
	$query=str_replace("%reminders%",$this->reminders,$query);
	$query=str_replace("%notes%",$this->notes,$query);
	$query=str_replace("%process%",$this->process,$query);
	$query=str_replace("%buttons%",$this->buttons,$query);
	$query=str_replace("%languages%",$this->languages,$query);
	foreach ($this->prefixes as $pref) {
		$query=str_replace($pref["prefix"],$pref["real_prefix"],$query);
	}
	if (@$this->config["charset"]["use_charset"]){
	 mysql_query('SET NAMES '.$this->config["charset"]["sql"]);
	 mysql_query('SET CHARSET '.$this->config["charset"]["sql"]);
	}
	if ($print_query)
		echo $query."<br>";
	$res=@mysql_query($query);
	//echo (getmicrotime()-time_start).'<br>';
	return $res;
}

function SQLConnect() {
//функция для подключения к MySQL базе. false - не подключились, true- подключились
if (!$link = @mysql_connect($this->config["db"]["host"],$this->config["db"]["user"],$this->config["db"]["password"])) { 
	die("not connect");
	return false;
} else {
 if (!@mysql_select_db($this->config["db"]["dbname"],$link)) {
	die("not select DB");
 	return false;
 } else {
 	return true;
 }
}
}

function getCount($query) {
	$res=$this->query($query);
	return @mysql_num_rows($res);
}

function Db() {
}

}
?>