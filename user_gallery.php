<?php
require_once 'classes/User.php';

session_start();

$user = User::get_current_user();

if (!$user->is_logged_in()) {
    header("refresh: 3; url=index.php");
    print 'сначала нужно войти';
    exit();
}

TPL::getInstance()->assign('user', $user);
TPL::getInstance()->display('user_gallery.tpl');