<?
$moduleinfo["caption"]="������ ����������";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["icon"]="articles.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/publications/";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_one_rubric"]=false;//�������� �� ���� �������� �����
$moduleinfo["publications_url"]="publications";

$moduleinfo["onpage"]=10;//�������� �� 10 ���������� �� ��������
$moduleinfo["onpage_admin"]=20;//�������� �� 20 ���������� � �������

$moduleinfo["comments"]=true;

/*��������� �������� ������*/
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["type"]="text";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["caption"]="��������� ����������";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["caption"]="������� ���������� ����������";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["caption"]="������ ���������� ����������";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["caption"]="��� meta description";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["caption"]="��� keywords";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["sql_type"]="LONGTEXT NULL";
/*����� ��������� �������� ������*/
?>