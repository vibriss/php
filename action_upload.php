<?php
require_once 'classes/Image.php';

session_start();

if (!empty($_POST) && !empty($_FILES)) {
    if (Image::upload($_SESSION['login'], $_FILES['file']) === true) {
        TPL::add_message("файл {$_FILES['file']['name']} загружен");
    } else {
        TPL::add_error(Image::upload($_SESSION['login'], $_FILES['file']));
    }
}

header("location:user_gallery.php");