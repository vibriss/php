<form method="POST" action="action_registration.php" enctype="multipart/form-data">
    логин <input name="login" type="text"><br>
    пароль <input name="password" type="password"><br>
    <button type="submit" name ="submit_registration">зарегистрироваться</button><br>
    {include file ="errors_and_messages.tpl"}
</form>
<div><a href="index.php">вернуться</a></div>