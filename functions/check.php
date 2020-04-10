<?php
require_once 'functions/db.php';

function debug($var) {
    echo '<pre>';
    if ($var) {
        print_r ($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}

function login_form_check($login, $password) {
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

function file_check($file) {
    $result['errors'] = [];
    
    if (empty($file['name'])) {
	return ['success' => false, 'errors' => []];
    }
    
    if ($file['size'] == 0) {
        $result['errors'][] = 'файл слишком большой';
    }
    
    $filename = strtolower($file['name']);
    $filename_array = explode('.', $filename);
    $extension = end($filename_array);
    if ($extension != 'jpg') {
        $result['errors'][] = 'к загрузке допускаются файлы с расширением .jpg';
    }
    
    $result['success'] = empty($result['errors']);
    return $result;
}