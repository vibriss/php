<?php
require_once 'functions/db.php';
require_once 'functions/check.php';

$image_id = $_GET['image'];

$image_path = get_img_path_by_id($image_id);
if ($image_path == null) {
    echo 'картинка отсутствует в базе';
    exit();
}

increment_img_counter($image_id);
echo '</br><a href="' . $_SERVER['HTTP_REFERER'] . '">вернуться</a>';
echo '<div><img src="' . $image_path. '"></div>';
echo '</br><a href="' . $_SERVER['HTTP_REFERER'] . '">вернуться</a>';