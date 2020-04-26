<?php 
require_once 'classes/TPL.php';
require_once 'classes/User.php';
require_once 'classes/MainGallery.php';

session_start();

try {
    $user = User::get_current_user();
    TPL::getInstance()->assign([
        'user'    => $user,
        'gallery' => new MainGallery(12, true)
    ]);
    TPL::getInstance()->display('index.tpl');
} catch (Exception $ex) {
    echo $ex->getMessage();
}