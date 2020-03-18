<?php

//подключение к бд
function connect() {
    require 'mysql_connect.php';       
    //подготовка к подключению
    $dsn = "mysql:host=$host;dbname=$dbname";
    //массив опций
    $opt = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ];
    //подключение к бд, создание объекта
    $pdo = new PDO($dsn, $login, $password, $opt);                    
    return $pdo;
}

//запрос к бд
function query($statement, $input_array) {
    $pdo = connect();
    $result = $pdo->prepare($statement);
    if ($result->execute($input_array)) {
        return $result;
    }
     else {
        return false;
     }
}

//вывод изображения
function show_preview ($image_path) {
    echo '<a href="' . $image_path. '"><img src="' . $image_path. '" width="200"></a>';
}

//проверка строки $string с именем $form_name на: заполненность, допустимость символов, длину 
function check_string($string, $form_name) {
    //проверка на пустоту
    if($string == null) {
        echo 'поле ввода ' . $form_name . ' не может быть пустым<br>';
        return false;
    }
     //проверка на допустимые символы
     elseif(!preg_match("/^[a-zA-Z0-9]+$/",$string)) {
        echo $form_name . ' может состоять только из букв английского алфавита и цифр<br>';
        return false;
     }
     //проверка на длину
     elseif(strlen($string) > 10) {
        echo $form_name . ' должен содержать не более 10 символов<br>';
        return false;
     }
     else {
        return true; 
     }
}

//проверка логина на присутствие в бд
function check_login($login) {
    $result = query ('SELECT count(login) FROM users WHERE login = ?', array($login));
    $result_array = $result->fetch();
    $result_string = implode($result_array);
    if($result_string < 1) {
        return false;
    }
     else {
        return true;
     }
} 

//проверка файла на: то, что он прикреплен, размер, расширение
function check_file($file) {
    //проверка на пустую форму
    if($file['name'] == null) {
	echo 'файл не прикреплён';
        return false;
    }
     //проверка размера файла
     elseif ($file['size'] == 0) {
        echo 'файл слишком большой, см. параметр upload_max_filesize в php.ini<br>';
        return false; 
     }
     //проверка расширения файла
     else {
        $filename = strtolower($file['name']);
    	//разбивка имени файла
        $filename_array = explode('.', $filename);
	//получение расширения
	$extension = end($filename_array);
	//проверка расширения
        if ($extension != 'jpg') {
            echo 'к загрузке допускаются файлы с расширением .jpg<br>';
            return false;
        }
         else {
            return true;
         }
     }
	
}