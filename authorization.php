<form method="POST" enctype="multipart/form-data">
логин <input name="login" type="text"><br>
пароль <input name="password" type="password"><br>
<button type="submit" name ="submit_login">войти</button>
</form>
<form method="POST" action="index.php" enctype="multipart/form-data">
<button type="submit">вернуться</button>
</form>

<?php
require 'functions.php';
session_start();
//если нажата кнопка "войти"
if(isset($_POST['submit_login'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    //вызов функции проверки логина и пароля
    if(check_string($login, 'логин') and check_string($password, 'пароль')) {
        //проверка на наличие учетной записи с введенным логином
        if(check_login($_POST['login']) == false) {
            echo "пользователя с таким логином не существует<br>";
        }
         else {
            //проверка соответствия пароля учетной записи 
            $result = query ('SELECT login, password FROM users WHERE login = ?',
                             array($_POST['login']));
            $result_array = $result->fetch();
            //если пароль совпадает, установить метку сессии
            if (password_verify($password, $result_array['password'])) {
                $_SESSION['login'] = $login;
                //перенаправление на главную страницу
                header("location: index.php");
            }
             else {
                 echo "неверный пароль<br>";
             }
         }    
    }
}
?>