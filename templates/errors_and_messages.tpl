{foreach $errors as $error}
    <div style="color:red">{$error}</div> 
{/foreach}
{foreach $messages as $message}
    <div style="color:green">{$message}</div> 
{/foreach}