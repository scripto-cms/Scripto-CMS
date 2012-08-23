<?
$moduleinfo["caption"]="������ ������";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/products/";
$moduleinfo["use_in_one_rubric"]=false;//�������� �� ���� �������� �����
$moduleinfo["icon"]="products.png";

$moduleinfo["show_price1"]=true;//�������� ��������� 1
$moduleinfo["show_price2"]=true;//�������� ��������� 2
$moduleinfo["show_count"]=false;//�������� ���������� ������
$moduleinfo["onpage"]=16;//�������� �� 20 ������� �� ��������
$moduleinfo["onpage_admin"]=16;//�������� �� 20 ������� �� ��������
$moduleinfo["shop_on"]=true;//�������� ������� �������� ��������
$moduleinfo["do_subcategory"]=true;//�������� ������ �� ������������
$moduleinfo["brand_url"]="brand";//������������� ��������, �� ������� ���������� ������ ��������������
$moduleinfo["type_url"]="type";//������������� ��������, �� ������� ���������� ���� �������
$moduleinfo["favorite_url"]="favorite";//������������� ��������, �� ������� �������� ��������� �������

//���� ��� ������� CSV
$moduleinfo["csv"][0]["id"]="csv_code";
$moduleinfo["csv"][0]["caption"]="��� ������";
$moduleinfo["csv"][1]["id"]="csv_product";
$moduleinfo["csv"][1]["caption"]="�������� ������";
$moduleinfo["csv"][2]["id"]="csv_price1";
$moduleinfo["csv"][2]["caption"]="���� 1";
$moduleinfo["csv"][3]["id"]="csv_price2";
$moduleinfo["csv"][3]["caption"]="���� 2";
$moduleinfo["csv"][4]["id"]="csv_price_default";
$moduleinfo["csv"][4]["caption"]="���������� ���������";
$moduleinfo["csv"][5]["id"]="csv_description";
$moduleinfo["csv"][5]["caption"]="������� ��������";
$moduleinfo["csv"][6]["id"]="csv_fulldescription";
$moduleinfo["csv"][6]["caption"]="������ ��������";
$moduleinfo["csv"][7]["id"]="csv_link";
$moduleinfo["csv"][7]["caption"]="������ �� ���� ������������� ������";
$moduleinfo["csv"][8]["id"]="csv_count";
$moduleinfo["csv"][8]["caption"]="�� ������ (���������� ������)";
$moduleinfo["csv"][9]["id"]="csv_caption";
$moduleinfo["csv"][9]["caption"]="�������� �������";

/*��������� �������� ������*/
$moduleinfo["tables"]["PRODUCTS"]["caption"]["type"]="text";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["caption"]="�������� ������";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["PRODUCTS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["PRODUCTS"]["content"]["caption"]="������� �������� ������";
$moduleinfo["tables"]["PRODUCTS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["caption"]="������ �������� ������";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["caption"]="��� meta description";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["caption"]="��� keywords";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["FIRMS"]["caption"]["type"]="text";
$moduleinfo["tables"]["FIRMS"]["caption"]["caption"]="�������� �����";
$moduleinfo["tables"]["FIRMS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FIRMS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FIRMS"]["description"]["type"]="solmetra";
$moduleinfo["tables"]["FIRMS"]["description"]["caption"]="�������� �����";
$moduleinfo["tables"]["FIRMS"]["description"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FIRMS"]["description"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["type"]="text";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["caption"]="�������� ����";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["ACTIONS"]["caption"]["type"]="text";
$moduleinfo["tables"]["ACTIONS"]["caption"]["caption"]="�������� �����";
$moduleinfo["tables"]["ACTIONS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["ACTIONS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["ACTIONS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["ACTIONS"]["content"]["caption"]="�������� �����";
$moduleinfo["tables"]["ACTIONS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["ACTIONS"]["content"]["sql_type"]="LONGTEXT NULL";
/*����� ��������� �������� ������*/
?>