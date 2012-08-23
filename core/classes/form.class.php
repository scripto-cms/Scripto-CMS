<?
class Form  {
var $frm=array();
var $err=array();
var $i=0;
var $n=0;
var $types=array("text","button","password","list","optionlist","check","optionbutton","textarea","hidden","solmetra","file","date","kcaptcha","separator","caption","time","preview");
	function Form() {
	//constructor
	}
	
	function addField($name,$error,$type="text",$value="",$defaultvalue="",$typeprov="",$id="",$required=1,$sample='',$params=array()) {
		if (!in_array($type,$this->types)) $type="text";

		$this->frm[$this->i]["type"]=$type;
		$this->frm[$this->i]["name"]=$name;
		$this->frm[$this->i]["error"]=$error;
		$this->frm[$this->i]["values"]=$value;
		$this->frm[$this->i]["value"]=$defaultvalue;
		$this->frm[$this->i]["eregi"]=$typeprov;
		$this->frm[$this->i]["id"]=$id;
		$this->frm[$this->i]["required"]=$required;
		$this->frm[$this->i]["sample"]=$sample;
		foreach ($params as $key=>$param) {
			$this->frm[$this->i][$key]=$param;
		}
		$this->i++;
	}
	
	function print_me($separator="<br>") {
	$text="";
		for ($j=0;$j<=sizeof($this->frm)-1;$j++) {
		if ($this->frm[$j]["type"]!="kcaptcha" && $this->frm[$j]["type"]!="hidden") {
		if ($this->frm[$j]["type"]=='caption') {
			$text.="$separator<b>".$this->frm[$j]["name"]."</b>".$separator.$separator;
		} elseif (
		($this->frm[$j]["type"]=='list') ||
		($this->frm[$j]["type"]=='optionbutton')
		) {
		$v="";
			foreach ($this->frm[$j]["values"] as $val) {
				if ($val["id"]==$this->frm[$j]["value"]) {
					$v=$val["name"];
					break;
				}
			}
			$text.=$this->frm[$j]["name"]." : ".$v.$separator;
		} elseif ($this->frm[$j]["type"]=="solmetra") {
			$text.="$separator<b>".$this->frm[$j]["name"]."</b>".$this->frm[$j]["real_value"].$separator;
		} elseif ($this->frm[$j]["type"]=="date") {
$text.="$separator<b>".$this->frm[$j]["name"]." :  </b>".$this->frm[$j]["value"][0]."/".$this->frm[$j]["value"][1]."/".$this->frm[$j]["value"][2].$separator;
		} elseif ($this->frm[$j]["type"]=="time") {
$text.="$separator<b>".$this->frm[$j]["name"]." :  </b>".$this->frm[$j]["value"][0].":".$this->frm[$j]["value"][1].$separator;
		} elseif ($this->frm[$j]["type"]=="date") {
			$text.=$this->frm[$j]["name"]." : Файл был прикреплен к письму";
		} elseif ($this->frm[$j]["type"]=="check") {
			if ($this->frm[$j]["value"]==1) {
				$v="Да";
			} else {
				$v="Нет";
			}
			$text.=$this->frm[$j]["name"]." : ".$v.$separator;
		} else {
			$text.=$this->frm[$j]["name"]." : ".$this->frm[$j]["value"].$separator;
		}
		}
		}
	return $text;
	}
	
	function Compile() {
		for ($j=0;$j<=sizeof($this->frm)-1;$j++) {
			if (($this->frm[$j]["type"]!='date') && ($this->frm[$j]["type"]!='solmetra') && ($this->frm[$j]["type"]!='time') && ($this->frm[$j]["type"]!='preview')) {
			if ($this->frm[$j]["required"]) {
			if (!preg_match($this->frm[$j]["eregi"],$this->frm[$j]["value"])) {
				$this->err[$this->n]["description"]=$this->frm[$j]["error"];
				$this->n++;
				$this->frm[$j]["iserror"]=1;
			}
			} else {
			if (!empty($this->frm[$j]["value"])) {
			if (!preg_match($this->frm[$j]["eregi"],$this->frm[$j]["value"])) {
				$this->err[$this->n]["description"]=$this->frm[$j]["error"];
				$this->n++;
				$this->frm[$j]["iserror"]=1;
			}
			}
			}
			} elseif ($this->frm[$j]["type"]=='date') {
			//если дата
					for ($i=0;$i<=2;$i++) {
						if (!preg_match($this->frm[$j]["eregi"],$this->frm[$j]["value"][$i])) {
	$this->err[$this->n]["description"]=$this->frm[$j]["name"].":".$this->frm[$j]["error"];
$this->n++;
							$this->frm[$j]["iserror"]=1;
						}
					}
			} elseif ($this->frm[$j]["type"]=='time') {
			//если время
					for ($i=0;$i<=1;$i++) {
						if (!preg_match($this->frm[$j]["eregi"],$this->frm[$j]["value"][$i])) {
	$this->err[$this->n]["description"]=$this->frm[$j]["name"].":".$this->frm[$j]["error"];
$this->n++;
							$this->frm[$j]["iserror"]=1;
						}
					}
			}
		}
	if (sizeof($this->err)>0) {
	return $this->err;
	} else {
	return false;
	}
	}
	
	function addError($description) {
	global $n;
		$this->err[$this->n]["description"]=$description;
		$this->n++;
	}
	
	function Show() {
	
		return $this->frm;
	}
}
?>