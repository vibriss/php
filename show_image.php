<?php
require_once 'functions/utils.php';
require_once 'classes/image.php';
require_once 'classes/TPL.php';

try {
    $image = new Image(get_input_integer(INPUT_GET, 'image'));
    $image->increment_view_count();

    $template = TPL::getInstance();
    $template->assign([
        'return_url' => get_input_url(INPUT_SERVER, 'HTTP_REFERER'),
        'image'      => $image
    ]);
    $template->display('show_image.tpl');    
} catch (Exception $ex) {
    echo $ex->getMessage();
}