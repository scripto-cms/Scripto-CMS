<?
//Модуль Опросы, пользовательская часть
$this->doVote();
if (isset($_REQUEST["voters_pg"])) {
$voters_pg=@$_REQUEST["voters_pg"];
} else {
$voters_pg=0;
}
if ($this->thismodule["1dayvote"]) {
	$db->query("DELETE FROM %VOTERS_IP% where `date`!=DATE(NOW())");
}
if (!preg_match("/^[0-9]{1,}$/i",$voters_pg)) $voters_pg=0;
$my_ip=getIp();
if ($this->thismodule["onpage"]==0) $this->thismodule["onpage"]=10;
$ips=$this->getAllIps();
$voters=$this->getUserAllVoters($voters_pg+1,$this->thismodule["onpage"]);
$voters_pages=ceil($this->getCountVoters()/$this->thismodule["onpage"]);
foreach ($voters as $id_vote=>$key) {
	if (isset($ips[$id_vote])) {
		if (is_array($ips[$id_vote])) {
			if (in_array($my_ip,$ips[$id_vote])) {
				$voters[$id_vote]["may_vote"]=false;
			} else {
				$voters[$id_vote]["may_vote"]=true;
			}
		} else {
			$voters[$id_vote]["may_vote"]=true;
		}
	} else {
		if ($voters[$id_vote]["current"]) {
			$voters[$id_vote]["may_vote"]=true;
		} else {
			$voters[$id_vote]["may_vote"]=false;
		}
	}
}
$voters_arr=array();
for ($x=0;$x<$voters_pages;$x++)
	$voters_arr[]=$x;
$smarty->assign("voters_pages",$voters_arr);
$smarty->assign("voters_page",$voters_pg);
$smarty->assign("voters",$voters);
?>