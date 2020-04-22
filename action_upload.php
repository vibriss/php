<?php
require_once 'classes/DB.php';
require_once 'classes/TPL.php';
require_once 'classes/User.php';

function upload($login, $file) {
    $result = file_check($file);
    if (!$result['success']) {
        return $result;
    }
    $file_name = generate_next_filename();
    move_uploaded_file($file['tmp_name'], __DIR__ . '\\img\\' . $file_name);
    
    DB::getInstance()->insert('INSERT into images (user_id, name) values (:user_id, :name)',
           ['user_id' => User::get_user_id_by_login($login), 'name' => $file_name]);
    return ['success' => true, 'errors' => []];
}

function file_check($file) {
    $result['errors'] = [];
    
    if (empty($file['name'])) {
	return ['success' => false, 'errors' => []];
    }
    
    if ($file['size'] == 0) {
        $result['errors'][] = 'файл слишком большой';
    }
    
    $filename = strtolower($file['name']);
    $filename_array = explode('.', $filename);
    $extension = end($filename_array);
    if ($extension != 'jpg') {
        $result['errors'][] = 'к загрузке допускаются файлы с расширением .jpg';
    }
    
    $result['success'] = empty($result['errors']);
    return $result;
}

function generate_next_filename() {
    return uniqid() . '.jpg';
}

session_start();

$action_result = ['action' => '', 'success' => false, 'errors' => [], 'messages' => []];
if (!empty($_POST) && isset($_POST['submit_upload'])) {
    $action_result['action'] = 'upload';
    $action_result = array_merge($action_result, upload($_SESSION['login'], $_FILES['file']));
    if ($action_result['success']) {
        $action_result['messages'][] = "файл {$_FILES['file']['name']} загружен";
    }
}

$_SESSION['messages'] = $action_result['messages'];
$_SESSION['errors'] = $action_result['errors'];

header("location:user_gallery.php");