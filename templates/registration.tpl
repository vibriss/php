<form method="POST" enctype="multipart/form-data">
    логин <input name="login" type="text"><br>
    пароль <input name="password" type="password"><br>
    <button type="submit" name ="submit_registration">зарегистрироваться</button><br>
    {foreach $errors as $error}
        <div>{$error}</div> 
    {/foreach}
</form>
<div><a href="index.php">вернуться</a></div>