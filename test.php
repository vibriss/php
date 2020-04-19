<?php
require_once 'classes/DB.php';
require_once 'functions/utils.php';
require_once 'classes/user.php';

session_start();
User::get_current_user()->gallery()->show();

exit;
/*
try {
    
    debug($db->get_user_gallery_data('111'));
        echo 'рас ';
        
} catch (Exception $ex) {
    echo $ex->getMessage();
}*/
/*
//контроллер
class BooksController extends AppController {
    function list($category) {
        $this->set('books', $this->Book->findAllByCategory($category));
    }
    function add() {
        
    }
    function delete() {
        
    }
}*/