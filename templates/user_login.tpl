{if $user->is_logged_in()}
    <div>вы вошли как {$user->get_login()|escape}</div> {*TODO нужно escape*}
    <form method="POST" action="action_logout.php" enctype="multipart/form-data">
        <button type="submit" name="submit_logout">выйти</button>
    </form>
{else}
    <form method="POST" action="action_login.php" enctype="multipart/form-data">
        <div>логин<input name="login" type="text"></div>
        <div>пароль<input name="password" type="password"></div>
        <button type="submit" name ="submit_login">войти</button>
    </form>
    {if !empty($errors)}
        {foreach $errors as $error}
            <div>{$error}</div> 
        {/foreach}
    {/if}
    <a href="action_registration.php">регистрация</a>
{/if}
