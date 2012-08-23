<?
$moduleinfo["caption"]="������ �������";
$moduleinfo["url"]="http://www.scripto-cms.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["icon"]="objects.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/objects/";
$moduleinfo["use_in_one_rubric"]=false;//�������� �� ���� �������� �����
$moduleinfo["onpage_admin"]=40;

$moduleinfo["eregi"]["text"]='/^[^`]{1,}$/i';
$moduleinfo["eregi"]["digital"]='/^[0-9]{1,}$/i';
$moduleinfo["eregi"]["pricerub"]='/^[0-9]{1,}$/i';
$moduleinfo["eregi"]["priceusd"]='/^[0-9]{1,}$/i';
$moduleinfo["eregi"]["english"]='/^[A-Za-z]{1,}$/i';
$moduleinfo["eregi"]["link"]='/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i';
$moduleinfo["eregi"]["email"]='/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i';
$moduleinfo["eregi"]["icq"]='/^[0-9-]{1,14}$/i';
$moduleinfo["eregi"]["phone"]='/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i';
$moduleinfo["eregi"]["checkbox"]='/^[0-9]{1}$/i';
$moduleinfo["eregi"]["textarea"]='/^[^`]{1,}$/i';

$moduleinfo["types"][0]["id"]='none';
$moduleinfo["types"][0]["name"]='���';
$moduleinfo["types"][1]["id"]='text';
$moduleinfo["types"][1]["name"]='��������� ����';
$moduleinfo["types"][2]["id"]='pricerub';
$moduleinfo["types"][2]["name"]='��������� (���.)';
$moduleinfo["types"][3]["id"]='priceusd';
$moduleinfo["types"][3]["name"]='��������� ($)';
$moduleinfo["types"][4]["id"]='digital';
$moduleinfo["types"][4]["name"]='������ �����';
$moduleinfo["types"][5]["id"]='english';
$moduleinfo["types"][5]["name"]='������ ���������� �������';
$moduleinfo["types"][6]["id"]='link';
$moduleinfo["types"][6]["name"]='������';
$moduleinfo["types"][7]["id"]='email';
$moduleinfo["types"][7]["name"]='E-mail';
$moduleinfo["types"][8]["id"]='icq';
$moduleinfo["types"][8]["name"]='����� ICQ';
$moduleinfo["types"][9]["id"]='phone';
$moduleinfo["types"][9]["name"]='����� ��������';
$moduleinfo["types"][10]["id"]='checkbox';
$moduleinfo["types"][10]["name"]='������ ��/���';
$moduleinfo["types"][11]["id"]='textarea';
$moduleinfo["types"][11]["name"]='������� ��������� ����';

$moduleinfo["listtypes"][0]["id"]='none';
$moduleinfo["listtypes"][0]["name"]='���';
$moduleinfo["listtypes"][1]["id"]='list';
$moduleinfo["listtypes"][1]["name"]='��������';

$moduleinfo["minimum_size"]["x"]=200;
$moduleinfo["minimum_size"]["y"]=200;
$moduleinfo["maximum_size"]["x"]=2000;
$moduleinfo["maximum_size"]["y"]=2000;

/*��������� �������� ������*/
$moduleinfo["tables"]["TYPES"]["caption"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption"]["caption"]="�������� ����";
$moduleinfo["tables"]["TYPES"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["congratulation_text"]["type"]="solmetra";
$moduleinfo["tables"]["TYPES"]["congratulation_text"]["caption"]="�����, ��� �������� ���������� ������� �������������";
$moduleinfo["tables"]["TYPES"]["congratulation_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["congratulation_text"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["TYPES"]["edit_text"]["type"]="solmetra";
$moduleinfo["tables"]["TYPES"]["edit_text"]["caption"]="�����, ��� �������� �������������� ������� �������������";
$moduleinfo["tables"]["TYPES"]["edit_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["edit_text"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["TYPES"]["caption1"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption1"]["caption"]="�������� ���� 1";
$moduleinfo["tables"]["TYPES"]["caption1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption2"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption2"]["caption"]="�������� ���� 2";
$moduleinfo["tables"]["TYPES"]["caption2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption3"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption3"]["caption"]="�������� ���� 3";
$moduleinfo["tables"]["TYPES"]["caption3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption3"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption4"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption4"]["caption"]="�������� ���� 4";
$moduleinfo["tables"]["TYPES"]["caption4"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption4"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption5"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption5"]["caption"]="�������� ���� 5";
$moduleinfo["tables"]["TYPES"]["caption5"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption5"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption6"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption6"]["caption"]="�������� ���� 6";
$moduleinfo["tables"]["TYPES"]["caption6"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption6"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption7"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption7"]["caption"]="�������� ���� 7";
$moduleinfo["tables"]["TYPES"]["caption7"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption7"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption8"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption8"]["caption"]="�������� ���� 8";
$moduleinfo["tables"]["TYPES"]["caption8"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption8"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption9"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption9"]["caption"]="�������� ���� 9";
$moduleinfo["tables"]["TYPES"]["caption9"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption9"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["caption10"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["caption10"]["caption"]="�������� ���� 10";
$moduleinfo["tables"]["TYPES"]["caption10"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["caption10"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["list_caption1"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["list_caption1"]["caption"]="�������� ������ 1";
$moduleinfo["tables"]["TYPES"]["list_caption1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["list_caption1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["list_caption2"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["list_caption2"]["caption"]="�������� ������ 2";
$moduleinfo["tables"]["TYPES"]["list_caption2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["list_caption2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["list_caption3"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["list_caption3"]["caption"]="�������� ������ 3";
$moduleinfo["tables"]["TYPES"]["list_caption3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["list_caption3"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["add_text"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["add_text"]["caption"]="�������� <...>";
$moduleinfo["tables"]["TYPES"]["add_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["add_text"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["view_all_text"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["view_all_text"]["caption"]="����� � ��������� <...>";
$moduleinfo["tables"]["TYPES"]["view_all_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["view_all_text"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["TYPES"]["fulllink_text"]["type"]="text";
$moduleinfo["tables"]["TYPES"]["fulllink_text"]["caption"]="����������� �������� <...>";
$moduleinfo["tables"]["TYPES"]["fulllink_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["TYPES"]["fulllink_text"]["sql_type"]="VARCHAR(255) NULL";


/*�������*/

$moduleinfo["tables"]["OBJ"]["caption"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["caption"]["caption"]="�������� �������";
$moduleinfo["tables"]["OBJ"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["small_content"]["type"]="solmetra";
$moduleinfo["tables"]["OBJ"]["small_content"]["caption"]="������� ����������";
$moduleinfo["tables"]["OBJ"]["small_content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["small_content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["OBJ"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["OBJ"]["content_full"]["caption"]="������ ����������";
$moduleinfo["tables"]["OBJ"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["OBJ"]["title"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["title"]["caption"]="��� title";
$moduleinfo["tables"]["OBJ"]["title"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["title"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["OBJ"]["meta"]["caption"]="��� meta";
$moduleinfo["tables"]["OBJ"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["meta"]["sql_type"]="TEXT NULL";

$moduleinfo["tables"]["OBJ"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["OBJ"]["keywords"]["caption"]="��� keywords";
$moduleinfo["tables"]["OBJ"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["keywords"]["sql_type"]="TEXT NULL";

$moduleinfo["tables"]["OBJ"]["value1"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value1"]["caption"]="�������� ���� 1";
$moduleinfo["tables"]["OBJ"]["value1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value2"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value2"]["caption"]="�������� ���� 2";
$moduleinfo["tables"]["OBJ"]["value2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value3"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value3"]["caption"]="�������� ���� 3";
$moduleinfo["tables"]["OBJ"]["value3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value3"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value4"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value4"]["caption"]="�������� ���� 4";
$moduleinfo["tables"]["OBJ"]["value4"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value4"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value5"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value5"]["caption"]="�������� ���� 5";
$moduleinfo["tables"]["OBJ"]["value5"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value5"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value6"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value6"]["caption"]="�������� ���� 6";
$moduleinfo["tables"]["OBJ"]["value6"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value6"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value7"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value7"]["caption"]="�������� ���� 7";
$moduleinfo["tables"]["OBJ"]["value7"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value7"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value8"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value8"]["caption"]="�������� ���� 8";
$moduleinfo["tables"]["OBJ"]["value8"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value8"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value9"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value9"]["caption"]="�������� ���� 9";
$moduleinfo["tables"]["OBJ"]["value9"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value9"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["value10"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["value10"]["caption"]="�������� ���� 10";
$moduleinfo["tables"]["OBJ"]["value10"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["value10"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["list1"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["list1"]["caption"]="�������� ������ 1";
$moduleinfo["tables"]["OBJ"]["list1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["list1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["list2"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["list2"]["caption"]="�������� ������ 2";
$moduleinfo["tables"]["OBJ"]["list2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["list2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["OBJ"]["list3"]["type"]="text";
$moduleinfo["tables"]["OBJ"]["list3"]["caption"]="�������� ������ 3";
$moduleinfo["tables"]["OBJ"]["list3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["OBJ"]["list3"]["sql_type"]="VARCHAR(255) NULL";
/*����� ��������� �������� ������*/
?>