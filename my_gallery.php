<?php
require_once 'functions/db.php';
require_once 'functions/check.php';
require_once 'functions/forms.php';

function upload($login, $file) {
    $result = file_check($file);
    if (!$result['success']) {
        return $result;
    }
    
    $file_name = generate_next_filename();
    move_uploaded_file($file['tmp_name'], __DIR__ . '\\img\\' . $file_name);
    
    add_filename_to_user_gallery($file_name, $login);
    
    return ['success' => true, 'errors' => []];
}

session_start();

if (!isset($_SESSION['login'])) {
    header("refresh: 3; url=index.php");
    echo 'сначала нужно войти';
    exit();
} else {
    echo 'вы вошли как ' . $_SESSION['login'];
    show_logout_form();
}

$action_result = ['action' => '', 'success' => false, 'errors' => [], 'messages' => []];
if (!empty($_POST) && isset($_POST['submit_upload'])) {
    $action_result['action'] = 'upload';
    $action_result = array_merge($action_result, upload($_SESSION['login'], $_FILES['file']));
    if ($action_result['success']) {
        $action_result['messages'][] = "файл {$_FILES['file']['name']} загружен";
    }
}

if (!empty($_POST) && isset($_POST['submit_delete'])) {
    $action_result['action'] = 'delete';
    foreach ($_POST['img_id_to_delete'] as $img_id) {
        $img_name = get_img_name_by_id($img_id);
        $action_result = array_merge($action_result, delete_image_by_id($_SESSION['login'], $img_id));
        if ($action_result['success']) {
            $action_result['messages'][] = "файл $img_name удалён";
        }
    }
    $action_result['success'] = count($action_result['messages']) > 0;
}

echo '<a href="index.php">вернуться</a>';
show_upload_form($action_result['errors']);
if ($action_result['success']) {
    echo implode('<br/>', $action_result['messages'])."<br/>";
}

show_my_gallery(get_user_gallery_data($_SESSION['login']));
