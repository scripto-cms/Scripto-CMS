<h1>Уважаемый пользователь!</h1>
<p>На сайте <a href="{$siteurl}" target="_blank">{$siteurl}</a> был совершен запрос на восстановление пароля к Вашей учетной записи {$u.login}.</p>
<p>Для того, чтобы сгенерировать новый пароль, Вам необходимо пройти по ссылке:</p>
<p><a href="{$siteurl}{if $current_lang}{$current_lang}{/if}{$thismodule.forgot_url}?activatekey={$activatekey}&login={$u.login}" target="_blank">{$siteurl}{if $current_lang}{$current_lang}{/if}{$thismodule.forgot_url}?activatekey={$activatekey}&login={$u.login}</a></p>
<p>Если Вы не запрашивали новый пароль , то не переходите по ссылке.</p>