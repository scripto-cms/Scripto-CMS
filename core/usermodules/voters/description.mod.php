<?
$moduleinfo["caption"]="������ ������";
$moduleinfo["url"]="http://www.scripto-cms.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["icon"]="vote.png";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_one_rubric"]=true;//������ ��� ����� �������
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/voters/";
$moduleinfo["voters_url"]="voters";

$moduleinfo["1dayvote"]=false;//������� ���������� ������� ���������
$moduleinfo["onpage"]=1;

/*��������� �������� ������*/
$moduleinfo["tables"]["VOTERS"]["vopros"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["vopros"]["caption"]="������ ������";
$moduleinfo["tables"]["VOTERS"]["vopros"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["vopros"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet1"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["caption"]="������� ������ 1";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet2"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["caption"]="������� ������ 2";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet3"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["caption"]="������� ������ 3";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet4"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["caption"]="������� ������ 4";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet5"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["caption"]="������� ������ 5";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["sql_type"]="VARCHAR(255) NULL";

/*����� ��������� �������� ������*/
?>