<?
/*
Русский языковой файл к галерее
*/
/*ОПИСАНИЕ ОШИБОК*/
define("CRITICAL_ERROR","<b>Внимание! Критическая ошибка:</b><br>");
$lang["error"]["not_set_template"]=CRITICAL_ERROR."Не установлен основной шаблон";
$lang["error"]["not_found_template"]=CRITICAL_ERROR."Не найден файл основного шаблона ";
$lang["error"]["error_module_loading"]=CRITICAL_ERROR."Ошибка подключения модуля";
$lang["error"]["notfind_module_file"]="Не найден файл(ы) модуля";
$lang["error"]["eregi_module"]="Обнаружены запрещенные символы в названии модуля";
$lang["error"]["ident_exist"]="Рубрика с таким идентификатором существует";
$lang["error"]["ident_exist2"]="Блок с таким идентификатором существует";
$lang["error"]["not_found_mainpage"]=CRITICAL_ERROR."Не установлена главная страница";
$lang["error"]["wrong_old_password"]="Указан неверный текущий пароль!";
$lang["error"]["module_install"]="Модуль не установлен";
$lang["error"]["basic_error"]="Ошибка";
$lang["error"]["template_no_writable"]="Шаблон не доступен для записи, сохранение невозможно.";
$lang["error"]["css_no_writable"]="Шаблон стилей не доступен для записи, сохранение невозможно.";
$lang["error"]["error_text"]='<br>Описание ошибки:<br>';
$lang["error"]["not_set"]='Не задано';
$lang["error"]["incorrect_date"]='Некорректно выбрана дата';
$lang["error"]["access_denied"]='Доступ закрыт';
$lang["error"]["caption"]='Ошибка';
$lang["error"]["not_create_tpl_site"]='Файл шаблона сайта не создан, т.к. на директорию с шаблонами не установлены права, достаточные для записи';
$lang["error"]["not_create_tpl_block"]='Файл шаблона блока не создан, т.к. на директорию с шаблонами не установлены права, достаточные для записи';

//Интерфейс
$lang["interface"]["not_correct_admin"]="Вы ввели неправильный логин и\или пароль";
$lang["interface"]["add_foto"]="Добавить фото";
$lang["interface"]["comments"]="Просмотр комментариев";
$lang["interface"]["objects"]="Галерея";
$lang["interface"]["payments"]="Оплаченные объекты";
$lang["interface"]["catalog"]="Рубрикатор сайта";
$lang["interface"]["settings"]="Настройки";
$lang["interface"]["help"]="Помощь";
$lang["interface"]["exit"]="Выход";
$lang["interface"]["tomain"]="На главную страницу административного интерфейса";
$lang["interface"]["root_name"]="Корневой раздел";
$lang["interface"]["add_category"]="Создать подраздел";
$lang["interface"]["delete_category"]="Удалить рубрику";
$lang["interface"]["edit_category"]="Редактировать рубрику";
$lang["interface"]["return_categories"]="<< назад к разделам";
$lang["interface"]["blocks"]="Блоки";
$lang["interface"]["delete_block"]="Удалить блок";
$lang["interface"]["delete_note"]="Удалить заметку";
$lang["interface"]["edit_block"]="Редактировать блок";
$lang["interface"]["text_block"]="Выбрать динамическое содержимое блока ";
$lang["interface"]["rss_block"]="RSS ленты блока ";
$lang["interface"]["random_photo_block"]="Выбрать случайные фото для блока";
$lang["interface"]["modules"]="Модули";
$lang["interface"]["install_module"]="Установить модуль";
$lang["interface"]["uninstall_module"]="Удалить модуль";
$lang["interface"]["update_module"]="Обновить модуль";
$lang["interface"]["control_module"]="Управлять модулем";
$lang["interface"]["category_module"]="Выбрать рубрику(и)";
$lang["interface"]["module_block"]="Управлять объектами блока ";
$lang["interface"]["templates"]="Шаблоны";
$lang["interface"]["edit_template"]="Редактировать шаблон";
$lang["interface"]["delete_template"]="Удалить шаблон из базы";
$lang["interface"]["main"]="Главная страница административного интерфейса";
$lang["interface"]["notes"]="Органайзер";
$lang["interface"]["welcome"]="Добро пожаловать в Scripto CMS!";
$lang["interface"]["success"]="Успешная авторизация";
$lang["interface"]["add_blank"]="Раздел(ы) успешно создан(ы)";
$lang["interface"]["data_saved"]="Данные успешно сохранены";
$lang["interface"]["catalog_sorted"]="Разделы отсортированы успешно";
$lang["interface"]["add_blank_caption"]="Добавить пустой раздел";
$lang["interface"]["add_blank_message"]="Укажите необходимое количество подразделов (пусто = 1 разделу)";
$lang["interface"]["category_module"]="Список разделов";
$lang["interface"]["add_category_module"]="Добавление нового раздела";
$lang["interface"]["edit_category_module"]="Редактирование раздела";
$lang["interface"]["rule_module"]="Управление модулями";
$lang["interface"]["data_save"]="Данные успешно сохранены!";
$lang["interface"]["category_module_ex"]="Управление выводом модуля в категории(ях)";
$lang["interface"]["edit_reminder"]="Редактировать напоминание";
$lang["interface"]["templates_module"]="Шаблоны сайта";
$lang["interface"]["add_template_site"]="Создание шаблона сайта";
$lang["interface"]["add_template_block"]="Создание шаблона блока";
$lang["interface"]["edit_main_buttons"]="Редактировать расположение кнопок";
$lang["interface"]["gotosite"]="Перейти на сайт";
$lang["interface"]["view_note"]="Просмотреть содержимое заметки";
$lang["interface"]["languages"]="Языковые версии";
$lang["interface"]["delete_language"]="Удалить языковую версию";
$lang["interface"]["requirements"]="Проверка совместимости хостинга";

//Диалоги
$lang["dialog"]["exit"]="Выйти из административного интерфейса?";
$lang["dialog"]["delete_category"]="Удалить рубрику и все ее содержимое?";
$lang["dialog"]["return_categories"]="Вернуться к просмотру разделов? (введенные Вами данные будут ПОТЕРЯНЫ)";
$lang["dialog"]["delete_block"]="Удалить блок и все его содержимое?";
$lang["dialog"]["uninstall_module"]="Удалить модуль? ВНИМАНИЕ! Все данные , связанные с этим модулем будут удалены!";
$lang["dialog"]["update_module"]="Обновить модуль? ВНИМАНИЕ! Возможна порча данных! Обязательно сохраните все Ваши данные перед обновлением модуля.";
$lang["dialog"]["delete_template"]='Удалить шаблон из базы? \nФайлы стилей и файлы шаблонов необходимо удалить самостоятельно.';
$lang["dialog"]["delete_note"]="Удалить заметку?";
$lang["dialog"]["delete_language"]="Удалить языковую версию?";

//Формы
$lang["forms"]["catalog"]["submit_name"]="Сохранить данные";
$lang["forms"]["catalog"]["title"]["caption"]="Название раздела";
$lang["forms"]["catalog"]["title"]["error"]="Неверно заполнено поле название раздела";
$lang["forms"]["catalog"]["title"]["sample"]="Путешествие в Европу";
$lang["forms"]["catalog"]["title"]["rules"]="Любые буквы и цифры, от 2-х до 255 символов";

$lang["forms"]["catalog"]["tags"]["caption"]="Теги";
$lang["forms"]["catalog"]["tags"]["error"]="Неверно заполнено поле теги";
$lang["forms"]["catalog"]["tags"]["sample"]="Москва, столица, город";
$lang["forms"]["catalog"]["tags"]["rules"]="Список тегов, через запятую";

$lang["forms"]["catalog"]["template"]["caption"]="Шаблон";
$lang["forms"]["catalog"]["template"]["error"]="Неверно выбран шаблон раздела";
$lang["forms"]["catalog"]["template"]["sample"]="Главная страница галереи";
$lang["forms"]["catalog"]["template"]["rules"]="";

$lang["forms"]["catalog"]["position"]["caption"]="Расположение в меню";
$lang["forms"]["catalog"]["position"]["error"]="Неверно выбрано расположение в меню";
$lang["forms"]["catalog"]["position"]["sample"]="Сверху";
$lang["forms"]["catalog"]["position"]["rules"]="Данные позиции должны быть прописаны в шаблоне";

$lang["forms"]["catalog"]["ident"]["caption"]="Идентификатор раздела";
$lang["forms"]["catalog"]["ident"]["error"]="Неверно заполнено поле идентификатор раздела";
$lang["forms"]["catalog"]["ident"]["sample"]="about , foto/samples";
$lang["forms"]["catalog"]["ident"]["rules"]="Цифры и латинские буквы, а также знаки: //,- и _ , от 3 до 255 символов";

$lang["forms"]["catalog"]["razdel"]["caption"]="Корневой раздел";
$lang["forms"]["catalog"]["razdel"]["error"]="Неверно выбран корневой раздел";
$lang["forms"]["catalog"]["razdel"]["sample"]="Мои фото -> Школа дизайна";

$lang["forms"]["catalog"]["content_type"]["caption"]="Тип содержимого";
$lang["forms"]["catalog"]["content_type"]["error"]="Неверно выбран тип содержимого";
$lang["forms"]["catalog"]["content_type"]["sample"]="Фотографии";

$lang["forms"]["catalog"]["visible"]["caption"]="Сделать раздел видимым";
$lang["forms"]["catalog"]["visible"]["error"]="Указано неверное значение видимости раздела";

$lang["forms"]["catalog"]["404"]["caption"]="Сделать страницей ошибки 404";
$lang["forms"]["catalog"]["404"]["error"]="Указано неверное значение сделать раздел страницей ошибки 404";

$lang["forms"]["catalog"]["file_content"]["caption"]="Сохранить контент в файл";
$lang["forms"]["catalog"]["file_content"]["error"]="Указано неверное значение параметра сохранить контент в файл";

$lang["forms"]["catalog"]["other"]["caption"]="Прочие настройки раздела";

$lang["forms"]["catalog"]["titletag"]["caption"]="Тэг &lt;title&gt; для раздела";
$lang["forms"]["catalog"]["titletag"]["error"]="Неверно заполнен тэг &lt;title&gt; для раздела";
$lang["forms"]["catalog"]["titletag"]["sample"]="Фотографии Ивана Дулина";
$lang["forms"]["catalog"]["titletag"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["catalog"]["metatag"]["caption"]="Тэг &lt;meta&gt; для раздела";
$lang["forms"]["catalog"]["metatag"]["error"]="Неверно заполнен тэг &lt;meta&gt; для раздела";
$lang["forms"]["catalog"]["metatag"]["sample"]="фотографии, коллекция фото";
$lang["forms"]["catalog"]["metatag"]["rules"]="Любые буквы и цифры , от 2-х до 1000 символов";

$lang["forms"]["catalog"]["metakeywords"]["caption"]="Тэг &lt;meta keywords&gt; для раздела";
$lang["forms"]["catalog"]["metakeywords"]["error"]="Неверно заполнен тэг &lt;meta keywords&gt; для раздела";
$lang["forms"]["catalog"]["metakeywords"]["sample"]="фотографии, коллекция фото";
$lang["forms"]["catalog"]["metakeywords"]["rules"]="Любые буквы и цифры , от 2-х до 1000 символов";

$lang["forms"]["catalog"]["content"]["caption"]="Содержание раздела";
$lang["forms"]["catalog"]["content"]["error"]="Неверно заполнено содержание раздела";

$lang["forms"]["catalog"]["subcontent"]["caption"]="Дополнительное содержание раздела";
$lang["forms"]["catalog"]["subcontent"]["error"]="Неверно заполнено дополнительное содержание раздела";

$lang["forms"]["catalog"]["rss_link"]["caption"]="Внешняя ссылка";
$lang["forms"]["catalog"]["rss_link"]["error"]="Неверно заполнена внешняя ссылка";
$lang["forms"]["catalog"]["rss_link"]["sample"]="http://news.yandex.ru";
$lang["forms"]["catalog"]["rss_link"]["rules"]="Любой адрес URL";

$lang["forms"]["catalog"]["future_post"]["caption"]="Отложенная публикация";
$lang["forms"]["catalog"]["future_post"]["error"]="Указано неверное значение отложенной публикации раздела";

$lang["forms"]["catalog"]["future_date"]["caption"]="Дата публикации раздела";
$lang["forms"]["catalog"]["future_date"]["error"]="Неверно указана дата публикации раздела";

$lang["forms"]["catalog"]["preview_width"]["caption"]="Ширина маленьких изображений пикселей";
$lang["forms"]["catalog"]["preview_width"]["error"]="Неверно заполнена ширина маленьких изображений";
$lang["forms"]["catalog"]["preview_width"]["sample"]="80";
$lang["forms"]["catalog"]["preview_width"]["rules"]="Цифры, если поле оставить незаполненным, либо 0 , то будут использованы основные настройки CMS";

$lang["forms"]["catalog"]["preview_height"]["caption"]="Высота маленьких изображений пикселей";
$lang["forms"]["catalog"]["preview_height"]["error"]="Неверно заполнена высота маленьких изображений";
$lang["forms"]["catalog"]["preview_height"]["sample"]="80";
$lang["forms"]["catalog"]["preview_height"]["rules"]="Цифры, если поле оставить незаполненным, либо 0 , то будут использованы основные настройки CMS";

$lang["forms"]["catalog"]["in_navigation"]["caption"]="Выводить в строке навигации";
$lang["forms"]["catalog"]["in_navigation"]["error"]="Указано неверное значение свойства выводить в строке навигации";

$lang["forms"]["catalog"]["is_registered"]["caption"]="Содержимое раздела доступно только зарегистрированным пользователям";
$lang["forms"]["catalog"]["is_registered"]["error"]="Указано неверное значение свойства содержимое раздела доступно только зарегистрированным пользователям";

//форма редактирования фоток, видео и т.п.
$lang["forms"]["object"]["submit_name"]="сохранить изменения";
$lang["forms"]["object"]["razdel"]["caption"]="Раздел объекта";
$lang["forms"]["object"]["razdel"]["error"]="Неверно выбран раздел объекта";
$lang["forms"]["object"]["razdel"]["sample"]="Мои фото -> Школа дизайна";

$lang["forms"]["object"]["title"]["caption"]="Название объекта";
$lang["forms"]["object"]["title"]["error"]="Неверно заполнено название объекта";
$lang["forms"]["object"]["title"]["sample"]="Путешествие в Европу, фото Парижа";
$lang["forms"]["object"]["title"]["rules"]="Любые буквы и цифры, от 2-х до 255 символов";

$lang["forms"]["object"]["visible"]["caption"]="Сделать объект видимым для посетителей";
$lang["forms"]["object"]["visible"]["error"]="Указано неверное значение видимости объекта";

$lang["forms"]["object"]["main"]["caption"]="Показывать объект на главной странице";
$lang["forms"]["object"]["main"]["error"]="Указано неверное значение показывать объект на главной странице";

$lang["forms"]["object"]["content"]["caption"]="Описание объекта";
$lang["forms"]["object"]["content"]["error"]="Неверно заполнено описание объекта";

$lang["forms"]["object"]["titletag"]["caption"]="Тэг &lt;title&gt; для объекта";
$lang["forms"]["object"]["titletag"]["error"]="Неверно заполнен тэг &lt;title&gt; для объекта";
$lang["forms"]["object"]["titletag"]["sample"]="Фотография Ивана Дулина";
$lang["forms"]["object"]["titletag"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["object"]["metatag"]["caption"]="Тэг &lt;meta&gt; для объекта";
$lang["forms"]["object"]["metatag"]["error"]="Неверно заполнен тэг &lt;meta&gt; для объекта";
$lang["forms"]["object"]["metatag"]["sample"]="фотографии, коллекция фото";
$lang["forms"]["object"]["metatag"]["rules"]="Любые буквы и цифры , от 2-х до 1000 символов";

$lang["forms"]["object_audio"]["genre"]["caption"]="Жанр";
$lang["forms"]["object_audio"]["genre"]["error"]="Неверно заполнен жанр";
$lang["forms"]["object_audio"]["genre"]["sample"]="Progressive House";
$lang["forms"]["object_audio"]["genre"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["object_audio"]["label"]["caption"]="Лейбл";
$lang["forms"]["object_audio"]["label"]["error"]="Неверно заполнен лейбл";
$lang["forms"]["object_audio"]["label"]["sample"]="Soyuz";
$lang["forms"]["object_audio"]["label"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["object_audio"]["prodolzhitelnost"]["caption"]="Продолжительность";
$lang["forms"]["object_audio"]["prodolzhitelnost"]["error"]="Неверно заполнена продолжительность трека";
$lang["forms"]["object_audio"]["prodolzhitelnost"]["sample"]="1 час 52 минуты";
$lang["forms"]["object_audio"]["prodolzhitelnost"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["object"]["external_url"]["caption"]="Внешний URL";
$lang["forms"]["object"]["external_url"]["error"]="Неверно заполнен внешний URL";
$lang["forms"]["object"]["external_url"]["sample"]="http://www.yandex.ru";
$lang["forms"]["object"]["external_url"]["rules"]="Любой http адрес";

$lang["forms"]["object_video"]["company"]["caption"]="Компания-производитель";
$lang["forms"]["object_video"]["company"]["error"]="Неверно заполнена компания-производитель";
$lang["forms"]["object_video"]["company"]["sample"]="Warner Brothers";
$lang["forms"]["object_video"]["company"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["object_video"]["prodolzhitelnost"]["caption"]="Продолжительность";
$lang["forms"]["object_video"]["prodolzhitelnost"]["error"]="Неверно заполнена продолжительность видео";
$lang["forms"]["object_video"]["prodolzhitelnost"]["sample"]="1 час 52 минуты";
$lang["forms"]["object_video"]["prodolzhitelnost"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["settings"]["submit_name"]="Сохранить настройки";

$lang["forms"]["settings"]["title"]["caption"]="Название сайта";
$lang["forms"]["settings"]["title"]["error"]="Неверно заполнено название сайта";
$lang["forms"]["settings"]["title"]["sample"]="Официальный сайт компании БМВ";
$lang["forms"]["settings"]["title"]["rules"]="Любые буквы и цифры, от 2-х до 255 символов";

$lang["forms"]["settings"]["email"]["caption"]="E-mail администратора";
$lang["forms"]["settings"]["email"]["error"]="Неверно заполнен e-mail администратора";
$lang["forms"]["settings"]["email"]["sample"]="admin@site.ru";
$lang["forms"]["settings"]["email"]["rules"]="E-mail адрес";

$lang["forms"]["settings"]["url"]["caption"]="URL сайта";
$lang["forms"]["settings"]["url"]["error"]="Неверно заполнен url сайта";
$lang["forms"]["settings"]["url"]["sample"]="http://host.ru";
$lang["forms"]["settings"]["url"]["rules"]="URL";

$lang["forms"]["settings"]["language"]["caption"]="Язык интерфейса административной части сайта";
$lang["forms"]["settings"]["language"]["error"]="Неверно выбран язык интерфейса административной части сайта";
$lang["forms"]["settings"]["language"]["sample"]="Русский язык";

$lang["forms"]["settings"]["content"]["caption"]="Описание сайта";
$lang["forms"]["settings"]["content"]["error"]="Неверно заполнено описание сайта";

$lang["forms"]["settings"]["titletag"]["caption"]="Тэг &lt;title&gt; (общий)";
$lang["forms"]["settings"]["titletag"]["error"]="Неверно заполнен тэг &lt;title&gt;";
$lang["forms"]["settings"]["titletag"]["sample"]="Продажа и обслуживание автомобилей БМВ";
$lang["forms"]["settings"]["titletag"]["rules"]="Любые буквы и цифры , от 2-х до 255 символов";

$lang["forms"]["settings"]["metatag"]["caption"]="Тэг &lt;meta&gt; (общий)";
$lang["forms"]["settings"]["metatag"]["error"]="Неверно заполнен тэг &lt;meta&gt;";
$lang["forms"]["settings"]["metatag"]["sample"]="БМВ, автомобили БМВ";
$lang["forms"]["settings"]["metatag"]["rules"]="Любые буквы и цифры , от 2-х до 1000 символов";

$lang["forms"]["settings"]["keywords"]["caption"]="Тэг &lt;keywords&gt; (общий)";
$lang["forms"]["settings"]["keywords"]["error"]="Неверно заполнен тэг &lt;keywords&gt;";
$lang["forms"]["settings"]["keywords"]["sample"]="БМВ, автосервис BMW";
$lang["forms"]["settings"]["keywords"]["rules"]="Любые буквы и цифры , от 2-х до 1000 символов";

$lang["forms"]["settings"]["smallx"]["caption"]="Ширина маленьких изображений";
$lang["forms"]["settings"]["smallx"]["error"]="Неверно заполнена ширина маленьких изображений";
$lang["forms"]["settings"]["smallx"]["sample"]="120";
$lang["forms"]["settings"]["smallx"]["rules"]="Размеры в пикселях";

$lang["forms"]["settings"]["smally"]["caption"]="Высота маленьких изображений";
$lang["forms"]["settings"]["smally"]["error"]="Неверно заполнена высота маленьких изображений";
$lang["forms"]["settings"]["smally"]["sample"]="120";
$lang["forms"]["settings"]["smally"]["rules"]="Размеры в пикселях";

$lang["forms"]["settings"]["mediumx"]["caption"]="Ширина средних изображений";
$lang["forms"]["settings"]["mediumx"]["error"]="Неверно заполнена ширина средних изображений";
$lang["forms"]["settings"]["mediumx"]["sample"]="500";
$lang["forms"]["settings"]["mediumx"]["rules"]="Размеры в пикселях";

$lang["forms"]["settings"]["mediumy"]["caption"]="Высота средних изображений";
$lang["forms"]["settings"]["mediumy"]["error"]="Неверно заполнена высота средних изображений";
$lang["forms"]["settings"]["mediumy"]["sample"]="500";
$lang["forms"]["settings"]["mediumy"]["rules"]="Размеры в пикселях";

$lang["forms"]["settings"]["set_new_password"]["caption"]="Поменять логин и пароль администратора";
$lang["forms"]["settings"]["set_new_password"]["error"]="Неверно выбрана опция смены логина и пароля администратора";

$lang["forms"]["settings"]["login"]["caption"]="Логин администратора";
$lang["forms"]["settings"]["login"]["error"]="Неверно заполнен логин администратора";
$lang["forms"]["settings"]["login"]["sample"]="admin";
$lang["forms"]["settings"]["login"]["rules"]="Латинские буквы (большие и маленькие) , а также цифры , от 4 до 20 символов";

$lang["forms"]["settings"]["oldpassword"]["caption"]="Текущий пароль администратора";
$lang["forms"]["settings"]["oldpassword"]["error"]="Неверно заполнен текущий пароль администратора";
$lang["forms"]["settings"]["oldpassword"]["sample"]="";
$lang["forms"]["settings"]["oldpassword"]["rules"]="Латинские буквы (большие и маленькие) , а также цифры , от 4 до 20 символов";

$lang["forms"]["settings"]["newpassword"]["caption"]="Новый пароль администратора";
$lang["forms"]["settings"]["newpassword"]["error"]="Неверно заполнен новый пароль администратора";
$lang["forms"]["settings"]["newpassword"]["sample"]="";
$lang["forms"]["settings"]["newpassword"]["rules"]="Латинские буквы (большие и маленькие) , а также цифры , от 4 до 20 символов";

$lang["forms"]["settings"]["cachesettings"]["caption"]="Настройки кеширования";

$lang["forms"]["settings"]["cache_on"]["caption"]="Включить кеширование модулей";
$lang["forms"]["settings"]["cache_on"]["error"]="Неверно выбрана опция кеширования модулей";

$lang["forms"]["settings"]["memcache_on"]["caption"]="Включить memcache (хостинг должен поддерживать)";
$lang["forms"]["settings"]["memcache_on"]["error"]="Неверно выбрана опция включить memcache";

$lang["forms"]["settings"]["memcache_server"]["caption"]="IP адрес сервера memcache";
$lang["forms"]["settings"]["memcache_server"]["error"]="Неверно заполнен IP адрес memcache сервера";
$lang["forms"]["settings"]["memcache_server"]["sample"]="127.0.0.1";
$lang["forms"]["settings"]["memcache_server"]["rules"]="Цифры и точки , максимум 15 символов";

$lang["forms"]["settings"]["memcache_port"]["caption"]="Порт сервера memcache";
$lang["forms"]["settings"]["memcache_port"]["error"]="Неверно заполнен порт сервера memcache";
$lang["forms"]["settings"]["memcache_port"]["sample"]="65300";
$lang["forms"]["settings"]["memcache_port"]["rules"]="Цифры, от 1 до 10 символов";

$lang["forms"]["settings"]["module_notes"]["caption"]="Выводить всплывающие сообщения в модулях";
$lang["forms"]["settings"]["module_notes"]["error"]="Неверно выбрана опция выводить всплывающие сообщения в модулях";

$lang["forms"]["settings"]["ips"]["caption"]="IP, допущенные к управлению";
$lang["forms"]["settings"]["ips"]["error"]="Неверно заполнены IP , допущенные к управлению";
$lang["forms"]["settings"]["ips"]["sample"]="158.89.79.2 ; 76.90.*.*";
$lang["forms"]["settings"]["ips"]["rules"]="IP адреса по одному на строчку";

$lang["forms"]["block"]["submit_name"]="Создать блок";
$lang["forms"]["block"]["edit_submit_name"]="Редактировать блок";

$lang["forms"]["block"]["title"]["caption"]="Название блока";
$lang["forms"]["block"]["title"]["error"]="Неверно заполнено поле название блока";
$lang["forms"]["block"]["title"]["sample"]="Контакты";
$lang["forms"]["block"]["title"]["rules"]="Любые буквы и цифры, от 2-х до 255 символов";

$lang["forms"]["block"]["template"]["caption"]="Шаблон блока";
$lang["forms"]["block"]["template"]["error"]="Неверно выбран шаблон блока";
$lang["forms"]["block"]["template"]["sample"]="Стандартный шаблон";
$lang["forms"]["block"]["template"]["rules"]="";

$lang["forms"]["block"]["ident"]["caption"]="Идентификатор блока";
$lang["forms"]["block"]["ident"]["error"]="Неверно заполнено поле идентификатор блока";
$lang["forms"]["block"]["ident"]["sample"]="sitemap";
$lang["forms"]["block"]["ident"]["rules"]="Цифры и латинские буквы , от 2 до 255 символов";

$lang["forms"]["block"]["type"]["caption"]="Тип блока";
$lang["forms"]["block"]["type"]["error"]="Неверно выбран тип блока";
$lang["forms"]["block"]["type"]["sample"]="Последние новости";
$lang["forms"]["block"]["type"]["rules"]="";

$lang["forms"]["block"]["show_mode"]["caption"]="Опции вывода блока";
$lang["forms"]["block"]["show_mode"]["error"]="Неверно выбрана опция вывода блока";
$lang["forms"]["block"]["show_mode"]["sample"]="Только на главной";
$lang["forms"]["block"]["show_mode"]["rules"]="";

$lang["forms"]["block"]["razdel"]["caption"]="Выводить в разделе";
$lang["forms"]["block"]["razdel"]["error"]="Неверно выбран раздел , в котором выводить блок";
$lang["forms"]["block"]["razdel"]["sample"]="Мои фото -> Школа дизайна";
$lang["forms"]["block"]["razdel"]["rules"]="Данная опция действует только , если опция вывода блока выбрана как показывать на определенной странице";

$lang["forms"]["block"]["content"]["caption"]="Содержание блока";
$lang["forms"]["block"]["content"]["error"]="Неверно заполнено содержание блока";

$lang["forms"]["block"]["visible"]["caption"]="Сделать блок видимым";
$lang["forms"]["block"]["visible"]["error"]="Указано неверное значение видимости блока";

$lang["forms"]["block"]["number_objects"]["caption"]="Количество выводимых объектов";
$lang["forms"]["block"]["number_objects"]["error"]="Неверно заполнено количество выводимых объектов";
$lang["forms"]["block"]["number_objects"]["sample"]="4";
$lang["forms"]["block"]["number_objects"]["rules"]="Цифры , данная опция действует для блоков, в которых выводятся динамические значения , либо объекты";

$lang["forms"]["notes"]["edit_reminder_submit_name"]="Редактировать напоминание";
$lang["forms"]["notes"]["add_reminder_submit_name"]="Добавить напоминание";
$lang["forms"]["notes"]["edit_note_submit_name"]="Редактировать заметку";
$lang["forms"]["notes"]["add_note_submit_name"]="Добавить заметку";

$lang["forms"]["templates"]["submit_name"]="Создать шаблон";

$lang["forms"]["templates"]["title"]["caption"]="Название шаблона";
$lang["forms"]["templates"]["title"]["error"]="Неверно заполнено название шаблона";
$lang["forms"]["templates"]["title"]["sample"]="Шаблон главной страницы";
$lang["forms"]["templates"]["title"]["rules"]="Любые буквы и цифры";

$lang["forms"]["templates"]["file"]["caption"]="Имя файла шаблона";
$lang["forms"]["templates"]["file"]["error"]="Неверно заполнено имя файла шаблона";
$lang["forms"]["templates"]["file"]["sample"]="index.tpl.html";
$lang["forms"]["templates"]["file"]["rules"]="Имя файла шаблона";

$lang["forms"]["templates"]["css"]["caption"]="Имя файла стиля шаблона";
$lang["forms"]["templates"]["css"]["error"]="Неверно заполнено имя стиля файла шаблона";
$lang["forms"]["templates"]["css"]["sample"]="style.css";
$lang["forms"]["templates"]["css"]["rules"]="Имя файла стиля шаблона";

$lang["forms"]["languages"]["submit_name"]="Создать языковую версию";

//Названия модулей
$lang["modules"]["catalog"]="Управление разделами";
$lang["modules"]["add_catalog"]="Создать раздел";
$lang["modules"]["objects"]="Галерея";
$lang["modules"]["upload"]="Загрузка новых файлов";
$lang["modules"]["edit_object"]="Редактирование объекта";
$lang["modules"]["settings"]="Настройки";
$lang["modules"]["blocks"]="Блоки";
$lang["modules"]["modules"]="Модули";
$lang["modules"]["modules_category"]="Выбор раздела для модуля";
$lang["modules"]["templates"]="Шаблоны";
$lang["modules"]["changepreview"]="Выбор изображения";
$lang["modules"]["changepreview_video"]="Выбор видеоролика";
$lang["modules"]["main"]="Добро пожаловать в административный интерфейс";
$lang["modules"]["notes"]="Органайзер";
$lang["modules"]["languages"]="Языковые версии сайта";
$lang["modules"]["add_language"]="Создать языковую версию";

//Сообщения о действиях
$lang["congratulation"]["rubric_add"]="Раздел создан успешно!";
$lang["congratulation"]["rubric_edit"]="Раздел отредактирован успешно!";
$lang["congratulation"]["rubric_delete"]="Раздел удален успешно!";
$lang["congratulation"]["rubric_sort"]="Разделы отсортированы успешно!";
$lang["congratulation"]["settings_save"]="Настройки сохранены";
$lang["congratulation"]["block_add"]="Блок добавлен!";
$lang["congratulation"]["block_edit"]="Блок отредактирован!";
$lang["congratulation"]["block_delete"]="Блок удален!";
$lang["congratulation"]["module_install"]="Модуль установлен успешно";
$lang["congratulation"]["module_uninstall"]="Модуль удален";
$lang["congratulation"]["module_update"]="Модуль обновлен";
$lang["congratulation"]["module_category_save"]="Опции показа модуля обновлены";
$lang["congratulation"]["reminder_add"]="Напоминание добавлено";
$lang["congratulation"]["reminder_edit"]="Напоминание отредактировано";
$lang["congratulation"]["template_add"]="Шаблон добавлен в базу";
$lang["congratulation"]["template_delete"]="Шаблон успешно удален из базы!";
$lang["congratulation"]["button_add"]="Кнопка создана успешно!";
$lang["congratulation"]["note_edit"]="Заметка отредактирована успешно!";
$lang["congratulation"]["note_add"]="Заметка добавлена успешно!";
$lang["congratulation"]["note_delete"]="Заметка удалена успешно!";

//Голосования
$lang["vote"]["already_vote"]="Вы уже голосовали!";
$lang["vote"]["vote_success"]="Ваш голос учтен!";
$lang["vote"]["vote_error"]="В процессе голосования произошла ошибка!";

//ajax
$lang["ajax"]["no_request"]="Ответ не получен";
$lang["ajax"]["error_loading"]="В ходе загрузки произошла ошибка";

//клиентское
$lang["mainpage"]="Главная страница";
$lang["user"]["tags_of_category"]="Теги категории";
$lang["sample"]="Например";
$lang["form_error"]="Внимание, допущены ошибки при заполнении";
$lang["goto_main"]="Перейти на главную страницу";
$lang["onmain"]="Вы находитесь на главной странице";
$lang["siteadmin"]="Связь с администратором сайта";
$lang["choose_sub_category"]="Выберите подраздел";
$lang["up"]="Вверх";

//системное
$lang["lang"]["not_set"]='не установлено';

//Контент страницы доступен только зарегистрированным пользователям
$lang["page_error"]="<p><b>Внимание!</b> Содержимое данного раздела доступно только зарегистрированным пользователям!</p>";

//Галерея
$lang["gallery"]["backto"]="Назад в раздел";
$lang["gallery"]["choosepage"]="Выберите страницу";
$lang["gallery"]["video_company"]="Компания-производитель";
$lang["gallery"]["length"]="Продолжительность";
$lang["gallery"]["description"]="Описание";
$lang["gallery"]["other_videos"]="Другие видео раздела";
$lang["gallery"]["other_photos"]="Другие фотографии раздела";
$lang["gallery"]["source"]="Источник";
$lang["gallery"]["listen"]="Слушать трек";
$lang["gallery"]["genre"]="Жанр";
$lang["gallery"]["label"]="Лейбл";
$lang["gallery"]["notset"]="Не указано";
$lang["gallery"]["author"]="Автор";
?>