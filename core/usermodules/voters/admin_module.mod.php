<?
/*
������ ������, ����������
������ ������ - 1.0
����������� - ������ �������
*/

$m_action=@$_REQUEST["m_action"];

switch ($m_action) {
	case "add":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_vote"])) {
				$id_vote=@$_REQUEST["id_vote"];
				$vote=$this->getVoteByID($id_vote);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$vopros=@$_REQUEST["vopros"];
				$otvet1=@$_REQUEST["otvet1"];
				$otvet2=@$_REQUEST["otvet2"];
				$otvet3=@$_REQUEST["otvet3"];
				$otvet4=@$_REQUEST["otvet4"];
				$otvet5=@$_REQUEST["otvet5"];
				$current=@$_REQUEST["current"];
				if ($current) {
					$current=1;
				} else {
					$current=0;
				}
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($vote) {
						$vopros=$vote["vopros"];
						$otvet1=$vote["otvet1"];
						$otvet2=$vote["otvet2"];
						$otvet3=$vote["otvet3"];
						$otvet4=$vote["otvet4"];
						$otvet5=$vote["otvet5"];
						$current=$vote["current"];
						$lang_values=$engine->generateLangArray("VOTERS",$vote);
					}
				} else {
					$vopros="";
					$otvet1="";
					$otvet2="";
					$otvet3="";
					$otvet4="";
					$otvet5="";
					$current=0;
					$lang_values=$engine->generateLangArray("VOTERS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("������ ������","������� �������� ������ ������","text",$vopros,$vopros,"/^[^`#]{2,255}$/i","vopros",1,"�������� �� ��� ��� ����?",array('size'=>'40','ticket'=>"����� ����� � �����"));

$frm->addField("������� ������ 1","������� �������� ����� 1","text",$otvet1,$otvet1,"/^[^`#]{2,255}$/i","otvet1",1,"��",array('size'=>'40','ticket'=>"����� ����� � �����"));

$frm->addField("������� ������ 2","������� �������� ����� 2","text",$otvet2,$otvet2,"/^[^`#]{2,255}$/i","otvet2",1,"���",array('size'=>'40','ticket'=>"����� ����� � �����"));

$frm->addField("������� ������ 3","������� �������� ����� 3","text",$otvet3,$otvet3,"/^[^`#]{2,255}$/i","otvet3",0,"",array('size'=>'40','ticket'=>"����� ����� � �����"));

$frm->addField("������� ������ 4","������� �������� ����� 4","text",$otvet4,$otvet4,"/^[^`#]{2,255}$/i","otvet4",0,"",array('size'=>'40','ticket'=>"����� ����� � �����"));

$frm->addField("������� ������ 5","������� �������� ����� 5","text",$otvet5,$otvet5,"/^[^`#]{2,255}$/i","otvet5",0,"",array('size'=>'40','ticket'=>"����� ����� � �����"));

$engine->generateLangControls("VOTERS",$lang_values,$frm);

$frm->addField("������� �������� �������","������� ������� �������� ������� �������� �������","check",$current,$current,"/^[0-9]{1,}$/i","current",0,"");

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_vote"])) {
$id_vote=$_REQUEST["id_vote"];
$frm->addField("","","hidden",$id_vote,$id_vote,"/^[^`]{0,}$/i","id_vote",1);
}
if ($mode=="edit") {
	$engine->addPath('�������������� ������','',false);
} else {
	$engine->addPath('�������� ������','',false);
}
			if (
$engine->processFormData($frm,"���������",$first
			)) {
				//��������� ��� �����������
				if ($mode=="edit") {
				 //�����������
				 if (isset($id_vote)) {
				 	if ($db->query("update %VOTERS% set `vopros`='".sql_quote($vopros)."' , otvet1='".sql_quote($otvet1)."',otvet2='".sql_quote($otvet2)."',otvet3='".sql_quote($otvet3)."',otvet4='".sql_quote($otvet4)."',otvet5='".sql_quote($otvet5)."',current=$current ".$engine->generateUpdateSQL("VOTERS",$lang_values)." where id_vote=$id_vote")) {
						//���������������
				//	   $modAction="view";
				   $engine->setCongratulation("������","����� �������������� �������!",3000);
				   $vote=$this->getVoteByID($id_vote);
				   $smarty->assign("vote",$vote);
				   $m_action="view";
					}
				 } else {
				 	//���������� ������
				 }
				} else {
				 //���������
 $add_id=$this->addVote($vopros,$otvet1,$otvet2,$otvet3,$otvet4,$otvet5,$current,$engine->generateInsertSQL("VOTERS",$lang_values));
				 if ($add_id!=false) {
				   //�������� �������!
				//   $modAction="view";
				   $engine->setCongratulation("������","����� ������ �������!",3000);
				   $vote=$this->getVoteByID(mysql_insert_id());
				   $smarty->assign("vote",$vote);
				   $m_action="view";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "save":
			$idvoters=@$_REQUEST["idvoters"];
			$vopros=@$_REQUEST["vopros"];
			$active=@$_REQUEST["active"];
			$n=0;
			foreach ($idvoters as $key=>$vote) {
				if (preg_match("/^[0-9]{1,}$/i",$vote)) {
					$vopr="";
					$activ="";
					if (isset($vopros[$key]))
						$vopr="`vopros`='".sql_quote($vopros[$key])."'";
					if (isset($active[$key])) {
						$activ=",`current`=1";
					} else {
						$activ=",`current`=0";
					}
				if ($db->query("update `%VOTERS%` set $vopr $activ where id_vote=$vote")) {
					$n++;
				}
				}
			}
			$engine->clearCacheBlocks($this->thismodule["name"]);
			$m_action="view";
			$engine->setCongratulation("������","������ ��������� (��������� $n �������)",5000);
	break;
	case "viewopros":
		$id_vote=@$_REQUEST["id_vote"];
		if (preg_match("/^[0-9]{1,}$/i",$id_vote)) {
			$vote=$this->getVoteByID($id_vote);
			$smarty->assign("vote",$vote);
		}
	break;
	case "delete":
		$id_vote=@$_REQUEST["id_vote"];
		if (preg_match("/^[0-9]{1,}$/i",$id_vote)) {
			if ($db->query("delete from %VOTERS% where id_vote=$id_vote")) {
				$engine->setCongratulation("������","����� ������!",5000);
				$m_action="view";
			}
		}
	break;
	default:
		$m_action="view";
}
if ($m_action=="view") {
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'',false);
	$engine->assignPath();
	$voters=$this->getAllVoters();
	$smarty->assign("voters",$voters);
}
$smarty->assign("m_action",$m_action);
?>