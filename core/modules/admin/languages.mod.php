<?
//���������� ������� � CMS
if (defined("SCRIPTO_GALLERY")) {
	$page["title"]=$lang["modules"]["languages"];
	$smarty->assign("page",$page);
	$smarty->assign("module_documentation","http://www.scripto-cms.ru/documentation/standart/languages/");
	$this->setAdminTitle($lang["modules"]["languages"]);
	$modAction=@$_REQUEST["modAction"];
	if ($modAction=="") $modAction="view";
	switch ($modAction) {
		case "delete":
			$id_language=@$_REQUEST["id_language"];
			if (preg_match("/^[0-9]{1,}$/i",$id_language)) {
				if ($this->deleteLanguage($id_language)) {
					$this->setCongratulation('','���� ������',5000);
					$modAction="view";
					$languages=$this->getListLanguages();
					$smarty->assign("languages",$languages);
				}
			}
		break;
		case "view":
			
		break;
		case "add":
			$this->addPath('�������� ������','/admin?module=languages',true);
			$this->addPath('���������� ����� ������','',false);
			$this->assignPath();
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$ident=@$_REQUEST["ident"];
			} else {
				$first=true;
				$caption="";
				$ident="";
			}
			$module["title"]=$lang["modules"]["add_language"];
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			$frm->addField('�������� �����','������� ������� �������� �����',"text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,'���������� ����',array('size'=>'40'));
			$frm->addField('����������� �����','������� ������� ����������� �����',"text",$ident,$ident,"/^[a-z]{1,8}$/i","ident",1,'en',array('size'=>'40'));
			if ($this->existLanguage($ident))
				$frm->addError('���� � ��������������� '.$ident.' ��� ����������');
			if (
$this->processFormData($frm,$lang["forms"]["languages"]["submit_name"],$first
			)) {
				if ($this->createLanguage($caption,$ident,0)) {
					$this->setCongratulation('','�������� ������ �������',5000);
					$modAction="view";
					$languages=$this->getListLanguages();
					$smarty->assign("languages",$languages);
					$this->clearPath();
				}
			}
		break;
	}
	if (isset($_REQUEST["lang"])) {
		$lang=@$_REQUEST["lang"];
		if (is_array($lang)) {
			foreach ($lang as $id_language=>$l) {
				$db->query("update `%languages%` set `caption`='".$l."' where id_language=$id_language");
			}
			$this->setCongratulation('','�������� �������� ������ ���������!',5000);
			$languages=$this->getListLanguages();
			$smarty->assign("languages",$languages);
		}
	}
	$smarty->assign("modAction",$modAction);
}
?>