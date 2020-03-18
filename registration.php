<form method="POST" enctype="multipart/form-data">
логин <input name="login" type="text"><br>
пароль <input name="password" type="password"><br>
<button type="submit" name ="submit_registration">зарегистрироваться</button>
</form>
<form method="POST" action="index.php" enctype="multipart/form-data">
<button type="submit">вернуться</button>
</form>

<?php
require 'functions.php';
session_start();
//если нажата кнопка "зарегистрироваться"
if(isset($_POST['submit_registration'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    //вызов функции проверки логина и пароля
    if(check_string($login, 'логин') and check_string($password, 'пароль')) {
        //проверка на уникальность логина
        if(check_login($_POST['login']) == true) {
            echo "пользователь с таким логином уже есть<br>";
        }
         //если пройдены все проверки, внести нового пользователя в бд
         else {
            //шифрование пароля (решил прикрутить, md5 почему-то не рекомендуют)
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $result = query ('INSERT into users (login, password) values (:login, :password)',
                             array(':login'=>$login, ':password'=>$password));
            if ($result) {
                echo "пользователь  добавлен<br>";
                //устанавить метку сессии
                $_SESSION['login'] = $login;
            }
         }
     }     
}
?>