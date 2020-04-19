<?php
require_once 'classes/DB.php';
require_once 'classes/MainGallery.php';
require_once 'classes/Gallery.php';
require_once 'classes/User.php';
require_once 'classes/TPL.php';
//require_once 'functions/check.php';
//require_once 'functions/forms.php';

//require_once 'functions/utils.php';

function gallery($img_count = 12, $random = null) {
        $query_string = 'SELECT img_id, name, count FROM images ';
        if ($random = 'random') {
            $query_string .= 'ORDER BY rand() ';
        }
        $query_string .= 'LIMIT ' . $img_count;
        $image_ids = DB::getInstance()->select_all($query_string, [], 'img_id');
        return new MainGallery($image_ids);
    }
    
session_start();

//$template = TPL::getInstance();
//$template->assign('name', 'Жора');
//$template->display('index.tpl');

//User::get_current_user()->gallery()->show();
gallery(12, 'random')->show();

exit();

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

