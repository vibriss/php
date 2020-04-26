{include file ="user_login.tpl"}
<div><a href="index.php">вернуться</a></div>
<form method="POST" action="action_upload.php" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit" name="submit_upload">загрузить</button>
    {include file ="errors_and_messages.tpl"}
</form>


{$user->gallery()->show()}