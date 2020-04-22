<?php
require_once 'classes/User.php';

session_start();

$attempt_login_result = ['success' => false, 'errors' => []];

if (!empty($_POST) && isset($_POST['submit_login'])) {
    $attempt_login_result = User::login($_POST['login'], $_POST['password']);
    $_SESSION['errors'] = $attempt_login_result['errors'];
    header("location:index.php");
    exit();
}