<?php
require_once 'classes/User.php';

session_start();

if (!empty($_POST) && isset($_POST['login']) && isset($_POST['password'])) {
    $registration_result = User::registration($_POST['login'], $_POST['password']);
    
    if ($registration_result === true) {
        $_SESSION['login'] = $_POST['login'];
        header("location:index.php");
        exit();
    } else {
        TPL::add_error($registration_result);
    }
}

header("location:registration.php");