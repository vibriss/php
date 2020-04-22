<?php
require_once 'classes/DB.php';
require_once 'classes/UserGallery.php';

class User{
    protected $_id;
    protected $_login;
    protected $_pwd_hash;
    protected $_gallery;
    protected static $_current_user; //TODO доделать

    public function __construct($id, $login, $pwd_hash) {
        $this->_id = $id;
        $this->_login = $login;
        $this->_pwd_hash = $pwd_hash;
    }
    
    public static function get_current_user() {
        if (isset($_SESSION['login'])) {
            return self::get_by_login($_SESSION['login']);
        } else {
            return new self(null, 'guest', null);
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
    
    public function is_logged_in() {
        return isset($this->_id);
    }
    
    public function get_login() {
        return $this->_login;
    }
    
    public function gallery() {
        if (isset($this->_gallery)) {
            return $this->_gallery;
        }
        $image_ids = DB::getInstance()->select_all('SELECT img_id FROM images JOIN users ON (images.user_id = users.user_id) WHERE login = ?', [$this->_login], 'img_id');
        return $this->_gallery = new UserGallery($this->_login, $image_ids);
    }
    
///////////////////////////////////////////////   
    
    public static function login($login, $password) {
        $result = self::login_form_check($login, $password);
        if (!$result['success']) {
            return $result;
        }
        if (!self::login_exists_in_db($login) || !self::password_match_login($login, $password)) {
            return ['success' => false, 'errors' => ['логин или пароль неверный']];
        }       
        $_SESSION['login'] = $login;
    }
    
    public static function login_form_check($login, $password) {
        $result['errors'] = [];
        $login = trim($login);
        $password = trim($password);

        if (!strlen($login) && !strlen($password)) {
            return ['success' => false, 'errors' => []];
        }

        if (!strlen($login)) {
            $result['errors'][] = 'поле ввода логина не может быть пустым';
        } else {
            if(!preg_match("/^[a-zA-Z0-9]+$/",$login)) {
                $result['errors'][] = 'логин может состоять только из букв английского алфавита и цифр';   
            }
            if(strlen($login) < 3) {
                $result['errors'][] = 'логин должен содержать не менее 3 символов';
            }
        }

        if (!strlen($password)) {
            $result['errors'][] = 'поле ввода пароля не может быть пустым';
        } else {
            if(strlen($password) < 3) {
                $result['errors'][] = 'пароль должен содержать не менее 3 символов';
            }
        }

        $result['success'] = empty($result['errors']);
        return $result;
    }
    
    public static function password_match_login($login, $password) {
        $result = DB::getInstance()->select_one('SELECT password FROM users WHERE login = ?', [$login], 'password');
        if ($result) {
            return password_verify($password, $result);
        } else {
            return false;
        }
    }
    
    public static function login_exists_in_db($login) {
        $result = DB::getInstance()->select_one('SELECT count(login) AS exist FROM users WHERE login = ?', [$login], 'exist');
        return $result == 1;
    }
    
    public static function get_user_id_by_login($login) {
        return DB::getInstance()->select_one('SELECT user_id FROM users WHERE login = ?', [$login], 'user_id');
    }
}