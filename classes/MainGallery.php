<?php
require_once 'classes/Image.php';

class MainGallery extends Gallery{
    const TEMPLATE = 'gallery/main.tpl';

    public function __construct($img_count = 12, $random = true) {
        parent::__construct(self::initialize($img_count, $random));
    }
    
    private static function initialize($img_count, $random) {
        $query_string = 'SELECT img_id FROM images ';
        if ($random) {
            $query_string .= 'ORDER BY rand() ';
        }
        $query_string .= 'LIMIT ' . $img_count;
        return DB::getInstance()->select_all($query_string, [], 'img_id');
    }
}