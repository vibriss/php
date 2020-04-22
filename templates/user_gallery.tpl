{include file ="user_login.tpl"}
<div><a href="index.php">вернуться</a></div>
<form method="POST" action="action_upload.php" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit" name="submit_upload">загрузить</button>
</form>
{if !empty($errors)}
    {foreach $errors as $error}
        <div>{$error}</div> 
    {/foreach}
{/if}
{if !empty($messages)}
    {foreach $messages as $message}
        <div>{$message}</div> 
    {/foreach}
{/if}
{$user->gallery()->show()}