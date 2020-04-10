<?php
require_once 'functions/db.php';
require_once 'functions/check.php';
require_once 'functions/forms.php';

function login($login, $password) {
    $result = login_form_check($login, $password);
    if (!$result['success']) {
        return $result;
    }
    if (!login_exists_in_db($login) || !password_match_login($login, $password)) {
        return ['success' => false, 'errors' => ['логин или пароль неверный']];
    }       
    
    $_SESSION['login'] = $login;
    header("location:index.php");
    exit();
}

session_start();

$attempt_login_result = ['success' => false, 'errors' => []];

if (!empty($_POST)) {
    if(isset($_POST['submit_login'])) {
        $attempt_login_result = login($_POST['login'], $_POST['password']);
    }
    if(isset($_POST['submit_logout'])) {
        logout();
    }
}

if (!isset($_SESSION['login'])) {
    show_login_form($attempt_login_result['errors']);
    echo '<a href="registration.php">регистрация</a>';
} else {
    echo 'вы вошли как ' . $_SESSION['login'];
    show_logout_form();
    echo '<a href="my_gallery.php">моя галерея</a>';
}

show_gallery(get_gallery_data(12, true));