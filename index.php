<form method="post" action="upload.php" enctype="multipart/form-data">
    <button type="submit">загрузить</button>
</form>

<form method="post" action="create.php" enctype="multipart/form-data">
    <button type="submit">создать бд</button>
</form>

<form method="post" action="index.php" enctype="multipart/form-data">
   номер страницы в выборке по 5 строк из таблицы: 
   <input type="number" name="page"/>
   <button type="submit">перейти</button>
</form>

<form method="post" action="index.php" enctype="multipart/form-data">
   id текста для просмотра анализа: 
   <input type="text" name="id"/>
   <button type="submit">просмотр</button>
</form>

<?php
//подключение к бд
$link = mysqli_connect("localhost", "root", "password", "userdb");                
//вывод отчета
if (!$link) {                                                                
    echo "ошибка подключения к БД: " . mysqli_error($link) . "<br>";
}

//если не введен номер страницы в поле
if (empty($_POST["page"])) {
    //вывести первую страницу, состоящую из первых пяти строк таблицы 
    $page = 1; 
}
 else {
    //если номер страницы введен, присвоить значение из поля
    $page = $_POST["page"];
}

//перечисление id в таблице uploaded_text
for ($i = ($page - 1) * 5 + 1; $i <= $page * 5; $i++) {
    //выбор текста из таблицы uploaded_text, соответствующего id
    $query = "select content from uploaded_text where id = '$i'";
    //запрос к бд
    $res = mysqli_query($link, $query);
    //получение массива с результатом
    $res_arr = mysqli_fetch_row($res);
    //если этот массив не пустой
    if (!empty($res_arr)) {
        //преобразование содержимого массива в строку
        $str = implode($res_arr);
        //если длина строки больше 30 символов
        if (strlen($str) > 30) {
            //укорачивание строки
            $str = mb_strimwidth($str, 0, 30);
            //в конце подставляется троеточие
            echo $i . ". " . $str . "...<br>";
        }
         else {
            //если длина строки меньше 30 символов, выводим без изменения
            echo $i . ". " . $str . "<br>";
         }
    }
}  

//если поле с text_id заполнено
if (!empty($_POST["id"])) {
    //далее будет осуществлен вывод подробностей анализа
    $id = $_POST["id"];
    //выбор текста из таблицы uploaded_text, соответствующего введенному id
    $query = "select content from uploaded_text where id = '$id'";
    //запрос к бд
    $res = mysqli_query($link, $query);
    //получение массива с результатом
    $res_arr = mysqli_fetch_row($res);
    //если по такому id есть текст
    if (!empty($res_arr)) {
        //преобразование содержимого массива в строку
        $str = implode($res_arr);
        //выбор даты из таблицы uploaded_text, соответствующего введенному id
        $query = "select date from uploaded_text where id = '$id'";
        //запрос к бд
        $res_date = mysqli_query($link, $query);
        //получение массива с результатом
        $res_date_arr = mysqli_fetch_row($res_date);
        //преобразование содержимого массива в строку
        $date = implode($res_date_arr);
        //вывод текста, даты
        echo "<br>текст: <br>" . $str . "<br>дата: " . $date . "<br>анализ: <br>";
        //выбор слов и соответствующего количества слов из таблицы word, соответствующих введенному text_d
        $query = "select word, count from word where text_id = '$id'";
        //запрос к бд
        $res_word = mysqli_query($link, $query);
        //вывод слов и их количества
        while ($word = mysqli_fetch_array($res_word)) {
            echo $word['word'] . " " . $word['count'] . "<br>";
        }
    }
}

//отключение от БД
//вывод отчета
if (!mysqli_close($link)) {                                                                 
    echo "ошибка отключения БД: " . mysqli_error($link) . "<br>";                 
 }

 ?>