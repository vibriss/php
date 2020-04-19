<?php
require_once 'classes/DB.php';
require_once 'classes/UserGallery.php';

class User{
    protected $_id;
    protected $_login;
    protected $_pwd_hash;
    protected $_gallery;

    public function __construct($id, $login, $pwd_hash) {
        $this->_id = $id;
        $this->_login = $login;
        $this->_pwd_hash = $pwd_hash;
    }
    
    public static function get_current_user() {
        if (isset($_SESSION['login'])) {
            return self::get_by_login($_SESSION['login']);
        } else {
            return null;
        }    
    }
    
    public static function get_by_login($login) {
        $user = DB::getInstance()->select_one('SELECT * FROM users WHERE login = ?', [$login]);
        if (!$user) {
            return null;
        }
        return $user = new User($user['user_id'], $user['login'], $user['password']);
    }
    
    public static function get_by_id($user_id) {
        $user = DB::getInstance()->select_one('SELECT * FROM users WHERE user_id = ?', [$user_id]);
        if (!$user) {
            return null;
        }
        return $user = new User($user['user_id'], $user['login'], $user['password']);
    }
    
    public function gallery() {
        if (isset($this->_gallery)) {
            return $this->_gallery;
        }
        $image_ids = DB::getInstance()->select_all('SELECT img_id FROM images JOIN users ON (images.user_id = users.user_id) WHERE login = ?', [$this->_login], 'img_id');
        return $this->_gallery = new UserGallery($this->_login, $image_ids);
    }
    
   
    
    
    
    
    
    
    
    public function password_match_login($login, $password) {
        $result = DB::getInstance()->select_one('SELECT password FROM users WHERE login = ?', [$login], 'password');
        if ($result) {
            return password_verify($password, $result);
        } else {
            return false;
        }
    }
    
    public function login_exists_in_db($login) {
        $result = DB::getInstance()->select_one('SELECT count(login) AS exist FROM users WHERE login = ?', [$login], 'exist');
        return $result == 1;
    }
    
    public function get_user_id_by_login($login) {
        return DB::getInstance()->select_one('SELECT user_id FROM users WHERE login = ?', [$login], 'user_id');
    }
}