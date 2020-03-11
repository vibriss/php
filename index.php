<form method="post" action="upload.php" enctype="multipart/form-data">
    <button type="submit">загрузить</button>
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

function connect() {
    //подключение файла с данными для входа
    require 'login_info.php';
    //подготовка к подключению
    $dsn = "mysql:host=$host;dbname=$db";
    //массив опций
    $opt = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ];
    //подключение к бд, создание объекта
    $pdo = new PDO($dsn, $login, $password, $opt);                    
    return $pdo;
}

$pdo = connect();

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
    //подготовка выражения для выбора текста из таблицы uploaded_text, соответствующего id
    $res = $pdo->prepare('SELECT content FROM uploaded_text WHERE id = ?');
    //выполнение
    $res->execute(array($i));
    //получение массива с результатом
    $res_arr = $res->fetch();
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
    //подготовка выражения для выбора текста из таблицы uploaded_text, соответствующего веденному id
    $res = $pdo->prepare('SELECT content FROM uploaded_text WHERE id = ?');
    //выполнение
    $res->execute(array($id));
    //получение массива с результатом
    $res_arr = $res->fetch();
    //если по такому id есть текст
    if (!empty($res_arr)) {
        //преобразование содержимого массива в строку
        $str = implode($res_arr);
        //подготовка выражения для выбора даты из таблицы uploaded_text, соответствующей введенному id
        $res = $pdo->prepare('SELECT date FROM uploaded_text WHERE id = ?');
        //выполнение
        $res->execute(array($id));
        //получение массива с результатом
        $res_arr = $res->fetch();
        //преобразование содержимого массива в строку
        $date = implode($res_arr);
        //вывод текста, даты
        echo "<br>текст: <br>" . $str . "<br>дата: " . $date . "<br>";
        echo "анализ: <br>";
        //подготовка выражения для выбора слов и количества из таблицы word, соответствующих введенному text_d
        $res = $pdo->prepare('SELECT word, count FROM word WHERE text_id = ?');
        //выполнение
        $res->execute(array($id));
        //получение массива с результатом
        while ($word = $res->fetch()) {
            //вывод результата
            echo $word['word'] . " " . $word['count'] . "<br>";
        }
    }
}
?>