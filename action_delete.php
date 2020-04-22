<?php
require_once 'classes/Image.php';

function delete_image_by_id($login, $img_id) {
//    $result = DB::getInstance()->select_one('SELECT user_id FROM images WHERE img_id = ?', [$img_id], 'user_id');
    $result = DB::getInstance()->select_one('SELECT login FROM users JOIN images ON (images.user_id = users.user_id) WHERE img_id = ?', [$img_id], 'login');
    if ($result != $_SESSION['login']) {
        return ['success' => false, 'errors' => ['нельзя удалять чужие фото']];
    } else {
        $image = new Image($img_id);
        DB::getInstance()->delete('DELETE FROM images WHERE img_id = ?', [$img_id]);
        unlink($image->get_path());
        return ['success' => true];
    }
}

session_start();

$action_result = ['action' => '', 'success' => false, 'errors' => [], 'messages' => []];
if (!empty($_POST) && isset($_POST['submit_delete'])) {
    $action_result['action'] = 'delete';
    debug($_POST['img_id_to_delete']);
    foreach ($_POST['img_id_to_delete'] as $img_id) {
        
        $image = new Image($img_id);
        $action_result = array_merge($action_result, delete_image_by_id($_SESSION['login'], $img_id));
        if ($action_result['success']) {
            $action_result['messages'][] = "файл {$image->get_name()} удалён";
        }
    }
    $action_result['success'] = count($action_result['messages']) > 0;
}

$_SESSION['messages'] = $action_result['messages'];
$_SESSION['errors'] = $action_result['errors'];

header("location:user_gallery.php");