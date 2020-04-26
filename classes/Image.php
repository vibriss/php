<?php
require_once 'classes/DB.php';
require_once 'classes/UserGallery.php';

class Image{
    const PATH = 'img\\';
    const TEMPLATE = 'image.tpl';
    
    protected $_id;
    protected $_name;
    protected $_path;
    protected $_login;
    protected $_count;

    public function __construct($id) {
        $image = DB::getInstance()->select_one('SELECT * FROM images JOIN users ON(users.user_id = images.user_id) WHERE img_id = ?', [$id]);
        if (!$image) {
            throw new Exception("картинка с id = $id отсутствует в базе");
        }
        $this->_id = $id;
        $this->_name = $image['name'];
        $this->_path = self::PATH . $image['name'];
        $this->_login = $image['login'];
        $this->_count = $image['count'];
    }

    public function get_id() {
        return $this->_id;
    }
    
    public function get_name() {
        return $this->_name;
    }
    
    public function get_path() {
        return $this->_path;
    }
    
    public function get_login() {
        return $this->_login;
    }
    
    public function get_view_count() {
        return $this->_count;
    }
    
    public function increment_view_count() {
        DB::getInstance()->update('UPDATE images SET count = count + 1 WHERE img_id = ?', [$this->_id]);
        return ++$this->_count;
    }
    
    public function show() {
        $template = TPL::getInstance();
        $template->assign('image', $this);
        $template->display(static::TEMPLATE);                                                                       
    }
    
    public function delete() { 
        DB::getInstance()->delete('DELETE FROM images WHERE img_id = ?', [$this->get_id()]);
        unlink($this->get_path());
        return "файл {$this->get_name()} удалён";
    }

    public static function upload($login, $file) {
        if (empty($file['name'])) {
            return '';
        }
        
        if ($file['size'] == 0) {
            return 'файл слишком большой';
        }
        
        $filename = strtolower($file['name']);
        $filename_array = explode('.', $filename);
        $extension = end($filename_array);
        if ($extension != 'jpg') {
            return 'к загрузке допускаются файлы с расширением .jpg';
        }

        $image_name = uniqid() . '.jpg';
        move_uploaded_file($file['tmp_name'], 'img\\' . $image_name);
        DB::getInstance()->insert('INSERT into images (user_id, name) values (:user_id, :name)',
            ['user_id' => User::get_user_id_by_login($login), 'name' => $image_name]);
        return true;
    }
}