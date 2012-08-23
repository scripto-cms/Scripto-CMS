<?
global $settings;

$moduleinfo["caption"]="Модуль формы";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["icon"]="forms.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/forms/";
$moduleinfo["use_in_one_rubric"]=false;//выводить во всех разделах сайта
$moduleinfo["mailadmin"]=$settings["mailadmin"];//e-mail администратора

$moduleinfo["inputs"][0]["id"]=0;
$moduleinfo["inputs"][0]["name"]="Текстовое поле";
$moduleinfo["inputs"][0]["type"]="text";
$moduleinfo["inputs"][1]["id"]=1;
$moduleinfo["inputs"][1]["name"]="Пароль";
$moduleinfo["inputs"][1]["type"]="password";
$moduleinfo["inputs"][2]["id"]=2;
$moduleinfo["inputs"][2]["name"]="Большое текстовое поле (textarea)";
$moduleinfo["inputs"][2]["type"]="textarea";
$moduleinfo["inputs"][3]["id"]=3;
$moduleinfo["inputs"][3]["name"]="Визуальный редактор";
$moduleinfo["inputs"][3]["type"]="solmetra";
$moduleinfo["inputs"][4]["id"]=4;
$moduleinfo["inputs"][4]["name"]="Чекбокс (checkbox)";
$moduleinfo["inputs"][4]["type"]="check";
$moduleinfo["inputs"][5]["id"]=5;
$moduleinfo["inputs"][5]["name"]="Радио кнопка (radio button)";
$moduleinfo["inputs"][5]["type"]="optionbutton";
$moduleinfo["inputs"][6]["id"]=6;
$moduleinfo["inputs"][6]["name"]="Выпадающий список";
$moduleinfo["inputs"][6]["type"]="list";
$moduleinfo["inputs"][7]["id"]=7;
$moduleinfo["inputs"][7]["name"]="Разделитель";
$moduleinfo["inputs"][7]["type"]="caption";
$moduleinfo["inputs"][8]["id"]=8;
$moduleinfo["inputs"][8]["name"]="Капча";
$moduleinfo["inputs"][8]["type"]="kcaptcha";
$moduleinfo["inputs"][9]["id"]=9;
$moduleinfo["inputs"][9]["name"]="Дата";
$moduleinfo["inputs"][9]["type"]="date";
$moduleinfo["inputs"][10]["id"]=10;
$moduleinfo["inputs"][10]["name"]="Время";
$moduleinfo["inputs"][10]["type"]="time";
$moduleinfo["inputs"][11]["id"]=11;
$moduleinfo["inputs"][11]["name"]="Файл";
$moduleinfo["inputs"][11]["type"]="file";

$moduleinfo["types"][0]["eregi"]="/^[^`#]{1,}$/i";
$moduleinfo["types"][0]["id"]=0;
$moduleinfo["types"][0]["name"]="Любой текст";
$moduleinfo["types"][1]["eregi"]="/^[a-zA-Z]{1,}$/i";
$moduleinfo["types"][1]["id"]=1;
$moduleinfo["types"][1]["name"]="Английские символы";
$moduleinfo["types"][2]["eregi"]="/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i";
$moduleinfo["types"][2]["id"]=2;
$moduleinfo["types"][2]["name"]="E-mail";
$moduleinfo["types"][3]["eregi"]="/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i";
$moduleinfo["types"][3]["id"]=3;
$moduleinfo["types"][3]["name"]="Адрес сайта";
$moduleinfo["types"][4]["eregi"]="/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i";
$moduleinfo["types"][4]["id"]=4;
$moduleinfo["types"][4]["name"]="Номер телефона";
$moduleinfo["types"][5]["eregi"]="/^[0-9]{1,}$/i";
$moduleinfo["types"][5]["id"]=5;
$moduleinfo["types"][5]["name"]="Только цифры";
$moduleinfo["types"][6]["eregi"]="/^[^`#]{1,}$/i";
$moduleinfo["types"][6]["id"]=6;
$moduleinfo["types"][6]["name"]="ФИО";


/*Настройки языковых версий*/
$moduleinfo["tables"]["FORMS"]["caption"]["type"]="text";
$moduleinfo["tables"]["FORMS"]["caption"]["caption"]="Название формы";
$moduleinfo["tables"]["FORMS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FORMS"]["caption_mail_admin"]["type"]="text";
$moduleinfo["tables"]["FORMS"]["caption_mail_admin"]["caption"]="Тема письма для администратора";
$moduleinfo["tables"]["FORMS"]["caption_mail_admin"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["caption_mail_admin"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FORMS"]["caption_mail_user"]["type"]="text";
$moduleinfo["tables"]["FORMS"]["caption_mail_user"]["caption"]="Тема письма для пользователя";
$moduleinfo["tables"]["FORMS"]["caption_mail_user"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["caption_mail_user"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FORMS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["FORMS"]["content"]["caption"]="Краткое описание формы";
$moduleinfo["tables"]["FORMS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["FORMS"]["success_admin"]["type"]="solmetra";
$moduleinfo["tables"]["FORMS"]["success_admin"]["caption"]="Сообщение об успешной отправке формы администратору";
$moduleinfo["tables"]["FORMS"]["success_admin"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["success_admin"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["FORMS"]["success_user"]["type"]="solmetra";
$moduleinfo["tables"]["FORMS"]["success_user"]["caption"]="Сообщение об успешной отправке формы пользователю";
$moduleinfo["tables"]["FORMS"]["success_user"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS"]["success_user"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["FORMS_INPUT"]["caption"]["type"]="text";
$moduleinfo["tables"]["FORMS_INPUT"]["caption"]["caption"]="Название элемента";
$moduleinfo["tables"]["FORMS_INPUT"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS_INPUT"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FORMS_INPUT"]["error_text"]["type"]="textarea";
$moduleinfo["tables"]["FORMS_INPUT"]["error_text"]["caption"]="Текст, при неправильном вводе значения";
$moduleinfo["tables"]["FORMS_INPUT"]["error_text"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS_INPUT"]["error_text"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FORMS_INPUT"]["tooltip"]["type"]="textarea";
$moduleinfo["tables"]["FORMS_INPUT"]["tooltip"]["caption"]="Пример правильного значения";
$moduleinfo["tables"]["FORMS_INPUT"]["tooltip"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FORMS_INPUT"]["tooltip"]["sql_type"]="VARCHAR(255) NULL";

/*Конец настройки языковых версий*/
?>