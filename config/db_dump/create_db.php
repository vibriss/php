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

//создание таблицы users 
$query_table1 = "create table users (                                 
            user_id int primary key auto_increment,
            login text,
            password text
            )";
//вывод отчета
if (mysqli_query($link, $query_table1)) {                                              
    echo "таблица users создана<br>";
}
 else {
    echo "ошибка создания таблицы: " . mysqli_error($link) . "<br>";                   
 }

//создание таблицы images
$query_table2 = "create table images (                                            
            img_id int primary key auto_increment,
            user_id int,                                                             
            image_name text,
            count int default 0
            )"; //??
//вывод отчета                                             
if (mysqli_query($link, $query_table2)) {                                       
    echo "таблица images создана<br>";
}
 else {
    echo "ошибка создания таблицы: " . mysqli_error($link) . "<br>";            
 }
