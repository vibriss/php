<?php
require_once 'classes/DB.php';
require_once 'classes/UserGallery.php';

class User{
    protected $_id;
    protected $_login;
    protected $_pwd_hash;
    protected $_gallery;
    protected static $_current_user;

    public function __construct($id, $login, $pwd_hash) {
        $this->_id = $id;
        $this->_login = $login;
        $this->_pwd_hash = $pwd_hash;
    }
 
    public static function get_current_user() {
        if (isset(self::$_current_user)) {
            return self::$_current_user;
        }
        if (isset($_SESSION['login'])) {
            self::$_current_user = self::get_by_login($_SESSION['login']);
        }
        if (!self::$_current_user) {
            self::$_current_user = new self(null, 'guest', null);
        }
        return self::$_current_user;
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
    
    public static function login($login, $password) {
        $login = trim($login);
        $password = trim($password);
        if (!strlen($login) || !strlen($password)) {
            return '';
        }
        
        if (!self::login_exists_in_db($login) || !self::password_matches_login($login, $password)) {
            return 'логин или пароль неверный';
        }      
        
        return true;
    }
    
    public static function registration($login, $password) {
        $login = trim($login);
        $password = trim($password);
        $errors = [];
        
        if (!strlen($login)) {
            $errors[] = 'поле ввода логина не может быть пустым';                
        } else {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
                $errors[] = 'логин может состоять только из букв английского алфавита и цифр';   
            }
            if (strlen($login) < 3) {
                $errors[] = 'логин должен содержать не менее 3 символов';
            }
            if (self::login_exists_in_db($login)) {
                $errors[] = 'логин уже занят';
            }
        }
        
        if (!strlen($password)) {
            $errors[] = 'поле ввода пароля не может быть пустым';
        } else {
            if(strlen($password) < 3) {
                $errors[] = 'пароль должен содержать не менее 3 символов';
            }
        }
        
        if (!empty($errors)) {
            return $errors;
        }
        
        DB::getInstance()->insert(
            'INSERT INTO users (login, password) values (:login, :password)',
            ['login' => $login, 'password' => password_hash($password, PASSWORD_DEFAULT)]
        );
        return true;
    }

    protected static function login_exists_in_db($login) {
        $result = DB::getInstance()->select_one('SELECT count(login) AS exist FROM users WHERE login = ?', [$login], 'exist');
        return $result == 1;
    }
    
    protected static function password_matches_login($login, $password) {
        $result = DB::getInstance()->select_one('SELECT password FROM users WHERE login = ?', [$login], 'password');
        if ($result) {
            return password_verify($password, $result);
        } else {
            return false;
        }
    }
    
    public static function get_user_id_by_login($login) {
        return DB::getInstance()->select_one('SELECT user_id FROM users WHERE login = ?', [$login], 'user_id');
    }
}