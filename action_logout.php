<?php
setcookie(session_name(), null, -1, '/');
session_destroy();
session_write_close();
header("location:index.php");

/*if (!empty($_POST)) {
    if(isset($_POST['submit_login'])) {
        $attempt_login_result = login($_POST['login'], $_POST['password']);
    }
    if(isset($_POST['submit_logout'])) {
        logout();
    }
}*/