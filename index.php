<?php
require 'functions.php';
session_start();

//если нет метки сессии, предложить войти/зарегистрироваться
if (isset($_SESSION['login']) == false) {
    ?>
    <form method="POST" action="authorization.php" enctype="multipart/form-data">
    <button type="submit">вход</button>
    </form>
    <form method="POST" action="registration.php" enctype="multipart/form-data">
    <button type="submit">регистрация</button>
    </form>
    <?php
}
 //если метка сессии установлена, вывести логин и кнопку выхода из учетки
 else {
    echo "вы вошли как " . $_SESSION['login'];
    ?>
    <form method="POST" enctype="multipart/form-data">
    <button type="submit" name="submit_logout">выйти из учетной записи</button>
    </form>
    <form method="POST" action="user_files.php" enctype="multipart/form-data">
    <button type="submit">моя галерея</button>
    </form>
    <?php
    //выход из учетной записи
    if(isset($_POST['submit_logout'])) {
        unset($_SESSION['login']);
        //обновление страницы после снятия метки сессии
        header("Refresh:0");
    }
 }
?>

<table border="1" cellspacing="20" cellpadding="10">  
    <tbody>
        <tr>
            
<?php
//переменная для отслеживания количества колонок, чтобы осуществлять вывод в новый ряд
$number_of_row = 0;
//вывод 12 случайных картинок в виде таблицы из трех колонок
$result = query ('SELECT image_name FROM images ORDER BY rand() LIMIT 12', null);
while ($result_array = $result->fetch()) {
    $image_path = "img\\" .$result_array['image_name'];
    if ($number_of_row < 3) {
        ?>
            
        <td>
               
        <?php
        show_preview($image_path);
        ?>
                
        </td>
            
        <?php
    }
     else {
        ?>
            
        </tr>
        <tr>
        <td>
                
        <?php
        $number_of_row = 0;
        show_preview($image_path);
        ?>
                
        </td>
            
        <?php
     }
    $number_of_row++;
}
?>
        
        </tr>
    </tbody>
</table>