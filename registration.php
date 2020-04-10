<?php
require_once 'functions/db.php';
require_once 'functions/check.php';
require_once 'functions/forms.php';

function registration($login, $password) {
    $result = login_form_check($login, $password);
    if (!$result['success']) {
        return $result;
    }
    if (login_exists_in_db($login)) {
        return ['success' => false, 'errors' => ['логин занят']];
    }       
    $password = password_hash($password, PASSWORD_DEFAULT);
    query('INSERT into users (login, password) values (:login, :password)',
           ['login'=>$login, 'password'=>$password]);
    $_SESSION['login'] = $login;
    header("location: index.php");
    exit();
}

session_start();

$attempt_registration_result = ['success' => false, 'errors' => []];

if (!empty($_POST) && isset($_POST['submit_registration'])) {
    $attempt_registration_result = registration($_POST['login'], $_POST['password']);
}

show_registration_form($attempt_registration_result['errors']);
echo '<a href="index.php">вернуться</a>';