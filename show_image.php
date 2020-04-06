<?php
require_once 'functions_db.php';

$image_id = $_GET['image'];

$image_path = get_img_path_by_id($image_id);
if ($image_path == null) {
    echo 'картинка отсутствует в базе';
    exit();
}

increment_img_counter($image_id);

echo '<img src="' . $image_path. '"></a>';