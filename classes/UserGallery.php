<?php
require_once 'classes/User.php';
require_once 'classes/Image.php';
require_once 'classes/Gallery.php';
require_once 'classes/TPL.php';

class UserGallery extends Gallery{
    const TEMPLATE = 'gallery/user.tpl';
    protected $_login; 
    
    public function __construct($login, $image_ids) {
        $this->_login = $login;
        parent::__construct($image_ids);
        foreach ($this->_images as $image) {
            if ($login != $image->get_login()) {
                throw new Exception ('картинка не этого пользователя');
            }
        }
    }
}