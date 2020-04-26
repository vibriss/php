<?php
require_once 'classes/Image.php';

session_start();

if (!empty($_POST) && !empty($_POST['img_id_to_delete'])) {
    foreach ($_POST['img_id_to_delete'] as $image_id) {
        try {
            $image = new Image($image_id);
            if ($image->get_login() == $_SESSION['login']) {
                TPL::add_message($image->delete());
            } else {
                TPL::add_error('файл не удалён: нельзя удалять чужие фото');
            }
        } catch (Exception $ex) {
        }
    }
}

header("location:user_gallery.php");