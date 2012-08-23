<?
$frm->addField($lang["users"]["login"]["caption"],$lang["users"]["login"]["error"],"text",$login,$login,"/^[a-zA-Z0-9]{5,10}$/i","login",1,"dmitry991984",array('size'=>'40','ticket'=>"Цифры и латинские буквы, от 5 до 10 символов"));

$frm->addField($lang["users"]["family"]["caption"],$lang["users"]["family"]["error"],"text",$family,$family,"/^[^`#]{2,255}$/i","family",0,$lang["users"]["family"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["name"]["caption"],$lang["users"]["name"]["error"],"text",$name,$name,"/^[^`#]{2,255}$/i","name",1,$lang["users"]["name"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["otch"]["caption"],$lang["users"]["otch"]["error"],"text",$otch,$otch,"/^[^`#]{2,255}$/i","otch",0,$lang["users"]["otch"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["city"]["caption"],$lang["users"]["city"]["error"],"text",$city,$city,"/^[^`#]{2,255}$/i","city",0,$lang["users"]["city"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["email"]["caption"],$lang["users"]["email"]["error"],"text",$email,$email,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","email",1,$lang["users"]["email"]["sample"],array('size'=>'40','ticket'=>""));

$frm->addField($lang["users"]["phone1"]["caption"],$lang["users"]["phone1"]["error"],"text",$phone1,$phone1,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone1",0,$lang["users"]["phone1"]["sample"],array('size'=>'40','ticket'=>""));

$frm->addField($lang["users"]["phone2"]["caption"],$lang["users"]["phone2"]["error"],"text",$phone2,$phone2,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone2",0,$lang["users"]["phone2"]["sample"],array('size'=>'40','ticket'=>""));
?>