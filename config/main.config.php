<?
//������� ���������������� ���� Scripto CMS
$config["version"]="Scripto CMS v 1.00";
$config["secretkey"]="<secret_key>";
$config["debug_mode"]=false;
$config["install"]=false;

//��������� �����
$config["content_type"]["image"]["name"]="����������";
$config["content_type"]["image"]["ident"]="image";
$config["content_type"]["image"]["admin_module"]="photo.processor.php";
$config["content_type"]["image"]["client_module"]="photo.processor.php";
$config["content_type"]["image"]["template_admin"]="photo.processor.tpl";
$config["content_type"]["image"]["template_client"]="photo.processor.tpl";

$config["content_type"]["video"]["name"]="����������";
$config["content_type"]["video"]["ident"]="video";
$config["content_type"]["video"]["admin_module"]="video.processor.php";
$config["content_type"]["video"]["client_module"]="video.processor.php";
$config["content_type"]["video"]["template_admin"]="video.processor.tpl";
$config["content_type"]["video"]["template_client"]="video.processor.tpl";

$config["content_type"]["audio"]["name"]="����������";
$config["content_type"]["audio"]["ident"]="audio";
$config["content_type"]["audio"]["admin_module"]="audio.processor.php";
$config["content_type"]["audio"]["client_module"]="audio.processor.php";
$config["content_type"]["audio"]["template_admin"]="audio.processor.tpl";
$config["content_type"]["audio"]["template_client"]="audio.processor.tpl";

$config["content_type"]["flash"]["name"]="����";
$config["content_type"]["flash"]["ident"]="flash";
$config["content_type"]["flash"]["admin_module"]="flash.processor.php";
$config["content_type"]["flash"]["client_module"]="flash.processor.php";
$config["content_type"]["flash"]["template_admin"]="flash.processor.tpl";
$config["content_type"]["flash"]["template_client"]="flash.processor.tpl";

$config["content_type"]["text"]["name"]="�����";
$config["content_type"]["text"]["ident"]="text";
$config["content_type"]["text"]["admin_module"]="text.processor.php";
$config["content_type"]["text"]["client_module"]="text.processor.php";
$config["content_type"]["text"]["template_admin"]="text.processor.tpl";
$config["content_type"]["text"]["template_client"]="text.processor.tpl";

/*
$config["content_type"]["media"]["name"]="��� ���� ����� ������";
$config["content_type"]["media"]["ident"]="media";
$config["content_type"]["media"]["admin_module"]="media.processor.php";
$config["content_type"]["media"]["client_module"]="media.processor.php";
$config["content_type"]["media"]["template_admin"]="media.processor.tpl";
$config["content_type"]["media"]["template_client"]="media.processor.tpl";
*/

$config["images_types"]=array("jpeg","jpg","gif","png");
$config["video_types"]=array("flv");
$config["flash_types"]=array("swf");
$config["music_types"]=array("mp3");

//��������� ������
$config["block_show_mode"][0]["id"]=0;
$config["block_show_mode"][0]["name"]="���������� �� ���� ���������";
$config["block_show_mode"][1]["id"]=1;
$config["block_show_mode"][1]["name"]="���������� �� ������������ ��������";

//���� ��������
$config["types"]=array("image","video","music","flash");

//���� ����
$config["menu_type"]["up"]="������";
$config["menu_type"]["down"]="�����";
$config["menu_type"]["left"]="�����";
$config["menu_type"]["right"]="������";

//��������� ��������� ������ ��� ������
$config["highlight"]["content"]["color"]="#402deb";//��������� ��������� ���� � ��������
$config["highlight"]["subcontent"]["color"]="#402deb";//��������� ��������� ���� � �����������
$config["highlight"]["caption"]["color"]="#402deb";//��������� ��������� ���� � ���������� ��������

//��������� ����������� � �������� �����, ���� ����� �������, � ���������� ���������
$config["denied_tags"][]="div";
$config["denied_tags"][]="body";
$config["denied_tags"][]="html";
$config["denied_tags"][]="head";
$config["denied_tags"][]="input";
$config["denied_tags"][]="button";
$config["denied_tags"][]="o:p";

//��������� �����, ������� �������� ������ � ����������
$config["cut_tags"][]="script";
$config["cut_tags"][]="style";
$config["cut_tags"][]="xml";
$config["cut_tags"][]="link";
$config["cut_tags"][]="meta";
$config["cut_tags"][]="title";
$config["cut_tags"][]="!";
$config["cut_tags"][]="form";
$config["cut_tags"][]="noscript";
$config["cut_tags"][]="iframe";

//�������������� �����
$config["registered_tags"][]="hide";
$config["registered_tags"][]="HIDE";

$config["replace_for_registered"] = '<p>������� �����:</p>$1';
$config["replace_for_not_registered"] = '<p>��� ��������� �������� ������ ��� ���������� ������������������ � ���������������� �� �����</p>';
$config["replace_for_not_users"] = '$1';

//���������, � ������� ���������� ������
$config["mail"]["charset"]="windows-1251";

//���� ������ �� ������� �������� �������
$config["button_types"][0]["id"]="button";
$config["button_types"][0]["name"]="����������� ������";
$config["button_types"][1]["id"]="separator";
$config["button_types"][1]["name"]="���������/�����������";
$config["button_types"][2]["id"]="greenbutton";
$config["button_types"][2]["name"]="������� ������";
$config["button_types"][3]["id"]="bluebutton";
$config["button_types"][3]["name"]="������� ������";
$config["button_types"][4]["id"]="webbutton";
$config["button_types"][4]["name"]="������ � ����� web 2.0";
$config["button_types"][5]["id"]="blackbutton";
$config["button_types"][5]["name"]="������ ������";
$config["button_types"][6]["id"]="orangebutton";
$config["button_types"][6]["name"]="��������� ������";
$config["button_types"][7]["id"]="yellowbutton";
$config["button_types"][7]["name"]="������ ������";

//���� ������ � ������ �� ������� �������� �������
$config["open_types"][0]["id"]=0;
$config["open_types"][0]["name"]="� ���� �� ����";
$config["open_types"][1]["id"]=1;
$config["open_types"][1]["name"]="� ����� ����";
$config["open_types"][2]["id"]=2;
$config["open_types"][2]["name"]="� javascript ����";

//������ ����������� �������
$config["modules"]["main"]="������ � ������� �������� ���. ����������";
$config["modules"]["settings"]="������ � ���������� �����";
$config["modules"]["blocks"]="������ � ���������� �������";
$config["modules"]["languages"]="������ � ���������� �������";
$config["modules"]["catalog"]="������ � ���������� ��������� �����";
$config["modules"]["modules"]="������ � ���������� �������� �����";
$config["modules"]["templates"]="������ � ���������� ��������� �����";
$config["modules"]["objects"]="������ � ������� �����";
$config["modules"]["notes"]="������ � ����������� �����";

//�������� �������� � ��������� ����� ��������
$config["template_help"]["enable"]=true;
?>