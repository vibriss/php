<?php
require_once 'classes/Image.php';

abstract class Gallery {
    const TEMPLATE = '';
    protected $_images = [];
    
    public function __construct($image_ids) {
        foreach ($image_ids as $image_id) {
            $this->_images[] = new Image($image_id);
        }
    }
    
    public function show() {
        $template = TPL::getInstance();
        $template->assign('images', $this->_images);
        return $template->fetch(static::TEMPLATE);
    }
}