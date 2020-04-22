<?php
require_once 'classes/DB.php';
require_once 'classes/TPL.php';
require_once 'classes/User.php';

function registration($login, $password) {
    $result = User::login_form_check($login, $password);
    if (!$result['success']) {
        return $result;
    }
    if (User::login_exists_in_db($login)) {
        return ['success' => false, 'errors' => ['логин занят']];
    }       
    $password = password_hash($password, PASSWORD_DEFAULT);
    DB::getInstance()->insert('INSERT into users (login, password) values (:login, :password)',
           ['login'=>$login, 'password'=>$password]);
    $_SESSION['login'] = $login;
    header("location:index.php");
    exit();
}

session_start();

$attempt_registration_result = ['success' => false, 'errors' => []];

if (!empty($_POST) && isset($_POST['submit_registration'])) {
    $attempt_registration_result = registration($_POST['login'], $_POST['password']);
    TPL::getInstance()->assign('errors', $attempt_registration_result['errors']);
}

TPL::getInstance()->display('registration.tpl');