<?php //TODO добавить try/catch
require_once 'classes/TPL.php';
require_once 'classes/MainGallery.php';
require_once 'classes/User.php';

session_start();

TPL::getInstance()->assign([
    'user'    => User::get_current_user(),
    'gallery' => new MainGallery(12, true)
]);
TPL::getInstance()->display('index.tpl');