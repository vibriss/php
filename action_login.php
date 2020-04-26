<?php
require_once 'classes/User.php';

session_start();

if (!empty($_POST) && isset($_POST['login']) && isset($_POST['password'])) {
    $login_result = User::login($_POST['login'], $_POST['password']);
    if ($login_result === true) {
        $_SESSION['login'] = $_POST['login'];
    } else {
        TPL::add_error($login_result);
    }
}

header("location:index.php");