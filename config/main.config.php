<?
//Главный конфигурационный файл Scripto CMS
$config["version"]="Scripto CMS v 1.00";
$config["secretkey"]="<secret_key>";
$config["debug_mode"]=false;
$config["install"]=false;

//Настройка типов
$config["content_type"]["image"]["name"]="Фотографии";
$config["content_type"]["image"]["ident"]="image";
$config["content_type"]["image"]["admin_module"]="photo.processor.php";
$config["content_type"]["image"]["client_module"]="photo.processor.php";
$config["content_type"]["image"]["template_admin"]="photo.processor.tpl";
$config["content_type"]["image"]["template_client"]="photo.processor.tpl";

$config["content_type"]["video"]["name"]="Видеофайлы";
$config["content_type"]["video"]["ident"]="video";
$config["content_type"]["video"]["admin_module"]="video.processor.php";
$config["content_type"]["video"]["client_module"]="video.processor.php";
$config["content_type"]["video"]["template_admin"]="video.processor.tpl";
$config["content_type"]["video"]["template_client"]="video.processor.tpl";

$config["content_type"]["audio"]["name"]="Аудиофайлы";
$config["content_type"]["audio"]["ident"]="audio";
$config["content_type"]["audio"]["admin_module"]="audio.processor.php";
$config["content_type"]["audio"]["client_module"]="audio.processor.php";
$config["content_type"]["audio"]["template_admin"]="audio.processor.tpl";
$config["content_type"]["audio"]["template_client"]="audio.processor.tpl";

$config["content_type"]["flash"]["name"]="Флеш";
$config["content_type"]["flash"]["ident"]="flash";
$config["content_type"]["flash"]["admin_module"]="flash.processor.php";
$config["content_type"]["flash"]["client_module"]="flash.processor.php";
$config["content_type"]["flash"]["template_admin"]="flash.processor.tpl";
$config["content_type"]["flash"]["template_client"]="flash.processor.tpl";

$config["content_type"]["text"]["name"]="Текст";
$config["content_type"]["text"]["ident"]="text";
$config["content_type"]["text"]["admin_module"]="text.processor.php";
$config["content_type"]["text"]["client_module"]="text.processor.php";
$config["content_type"]["text"]["template_admin"]="text.processor.tpl";
$config["content_type"]["text"]["template_client"]="text.processor.tpl";

/*
$config["content_type"]["media"]["name"]="Все типы медиа файлов";
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

//настройки блоков
$config["block_show_mode"][0]["id"]=0;
$config["block_show_mode"][0]["name"]="Показывать на всех страницах";
$config["block_show_mode"][1]["id"]=1;
$config["block_show_mode"][1]["name"]="Показывать на определенной странице";

//типы контента
$config["types"]=array("image","video","music","flash");

//типы меню
$config["menu_type"]["up"]="сверху";
$config["menu_type"]["down"]="снизу";
$config["menu_type"]["left"]="слева";
$config["menu_type"]["right"]="справа";

//Настройка подсветки текста при поиске
$config["highlight"]["content"]["color"]="#402deb";//подсветка найденных слов в контенте
$config["highlight"]["subcontent"]["color"]="#402deb";//подсветка найденных слов в субконтенте
$config["highlight"]["caption"]["color"]="#402deb";//подсветка найденных слов в заголовках разделов

//Настройка запрещенных в контенте тегов, теги будут удалены, а содержимое оставлено
$config["denied_tags"][]="div";
$config["denied_tags"][]="body";
$config["denied_tags"][]="html";
$config["denied_tags"][]="head";
$config["denied_tags"][]="input";
$config["denied_tags"][]="button";
$config["denied_tags"][]="o:p";

//Настройка тегов, которые вырезать вместе с содержимым
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

//Преобразование тегов
$config["registered_tags"][]="hide";
$config["registered_tags"][]="HIDE";

$config["replace_for_registered"] = '<p>Скрытый текст:</p>$1';
$config["replace_for_not_registered"] = '<p>Для просмотра скрытого текста Вам необходимо зарегистрироваться и авторизироваться на сайте</p>';
$config["replace_for_not_users"] = '$1';

//Кодировка, в которой отправлять письма
$config["mail"]["charset"]="windows-1251";

//Типы кнопок на главной странице админки
$config["button_types"][0]["id"]="button";
$config["button_types"][0]["name"]="Стандартная кнопка";
$config["button_types"][1]["id"]="separator";
$config["button_types"][1]["name"]="Заголовок/разделитель";
$config["button_types"][2]["id"]="greenbutton";
$config["button_types"][2]["name"]="Зеленая кнопка";
$config["button_types"][3]["id"]="bluebutton";
$config["button_types"][3]["name"]="Голубая кнопка";
$config["button_types"][4]["id"]="webbutton";
$config["button_types"][4]["name"]="Кнопка в стиле web 2.0";
$config["button_types"][5]["id"]="blackbutton";
$config["button_types"][5]["name"]="Черная кнопка";
$config["button_types"][6]["id"]="orangebutton";
$config["button_types"][6]["name"]="Оранжевая кнопка";
$config["button_types"][7]["id"]="yellowbutton";
$config["button_types"][7]["name"]="Желтая кнопка";

//Типы ссылок у кнопок на главной странице админки
$config["open_types"][0]["id"]=0;
$config["open_types"][0]["name"]="В этом же окне";
$config["open_types"][1]["id"]=1;
$config["open_types"][1]["name"]="В новом окне";
$config["open_types"][2]["id"]=2;
$config["open_types"][2]["name"]="В javascript окне";

//Список стандартных модулей
$config["modules"]["main"]="Доступ к главной странице адм. интерфейса";
$config["modules"]["settings"]="Доступ к настройкам сайта";
$config["modules"]["blocks"]="Доступ к управлению блоками";
$config["modules"]["languages"]="Доступ к управлению языками";
$config["modules"]["catalog"]="Доступ к управлению разделами сайта";
$config["modules"]["modules"]="Доступ к управлению модулями сайта";
$config["modules"]["templates"]="Доступ к управлению шаблонами сайта";
$config["modules"]["objects"]="Доступ к галерее сайта";
$config["modules"]["notes"]="Доступ к органайзеру сайта";

//включить помощник в генерации кодов шаблонов
$config["template_help"]["enable"]=true;
?>