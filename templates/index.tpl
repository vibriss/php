{include file ="user_login.tpl"}
{if $user->is_logged_in()}
    <div><a href="user_gallery.php">моя галерея</a></div>
{/if}
{$gallery->show()}