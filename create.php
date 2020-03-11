<?php

//подключение к БД
//вывод отчета
$link = mysqli_connect("localhost", "root", "hhhhhh");                          
if (!$link) {                                                                   
    echo "ошибка подключения к БД<br>";         
}

//создание бд
$query_db = "create database userdb";
//вывод отчета
if (mysqli_query($link, $query_db)) {
    echo "БД создана<br>";
} else {
    echo "ошибка создания БД: " . mysqli_error($link) . "<br>";
}

//выбор бд
$selected_db = mysqli_select_db($link, "userdb");                               //выбор БД
//вывод отчета
if ($selected_db) {                                                            
    echo "БД выбрана<br>";
}
 else {
    echo "ошибка выбора БД<br>"; 
 }

//создание таблицы uploaded_text 
$query_table1 = "create table uploaded_text (                                 
            id int primary key auto_increment,
            content text,
            date datetime,
            words_count int
            )";
//вывод отчета
if (mysqli_query($link, $query_table1)) {                                              
    echo "таблица uploaded_text создана<br>";
}
 else {
    echo "ошибка создания таблицы: " . mysqli_error($link) . "<br>";                   
 }

//создание таблицы word
$query_table2 = "create table word (                                            
            id int primary key auto_increment,                                                             
            text_id int,
            word text,
            count int
            )";
//вывод отчета                                             
if (mysqli_query($link, $query_table2)) {                                       
    echo "таблица word создана<br>";
}
 else {
    echo "ошибка создания таблицы: " . mysqli_error($link) . "<br>";            
 } 
?>

<form method="post" action="index.php" enctype="multipart/form-data">
    <button type="submit">вернуться</button>
</form>