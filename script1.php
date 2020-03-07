<?php
//функция, в которой анализируется текст
function text_analyse($str_input) {                                             
    //преобразование в нижний регистр
    $str_lowered = mb_strtolower($str_input);					
    //удаление пробелов из начала и конца строки
    $str_trimmed = trim($str_lowered);						
    //разделение строки на отдельные слова
    $arr = preg_split("/[\s,':\.]+/", $str_trimmed, null, PREG_SPLIT_NO_EMPTY);	
    //заполнение ассоциативного массива, ключами которого являются слова, а значениями - количество повторений
    $new_arr = array_count_values($arr);                                        
    //вывод результатов функции
    return [$new_arr, $arr];                                                    
}

//запрос к текстовому полю формы
$str_input = $_POST["text"];                                                    

//если текстовое поле не пустое, выполняется следующее:
if ($str_input != null) {                                                       
    
    //подключение к БД
    $link = mysqli_connect("localhost", "root", "password", "userdb");                
    //вывод отчета
    if ($link) {                                                                
        echo "БД подключена<br>";
    }
     else {
        echo "ошибка подключения к БД: " . mysqli_error($link) . "<br>";         
     }
    
    //вызов функции анализа текста (функция взята из прошлого задания)
    list($new_arr, $arr) = text_analyse($str_input);
    
    //подсчет количества слов
    $words_count = count($arr);
    
    //заполнение таблицы uploaded_text содержанием текстового поля, текущей датой, количеством слов в тексте
    $query = "insert into uploaded_text (content, date, words_count) values ('$str_input', now(), '$words_count')";
    //вывод отчета
    if (mysqli_query($link, $query)) {                                          
        echo "текст загружен в таблицу uploaded_text<br>";
    }
     else {
        echo "ошибка загрузки текста в таблицу uploaded_text: " . mysqli_error($link) . "<br>";                   
     }
    
    //выбор значения id из таблицы uploaded_text для последнего поля content(загруженный текст), совпадающего с новым введенным текстом
    $query = "select id from uploaded_text where content = '$str_input' order by id desc";
    //запрос к бд
    $res = mysqli_query($link, $query);
    //вывод отчета
    if ($res) {                                              
        echo "text_id получен<br>";
    }
     else {
        echo "ошибка получения text_id: " . mysqli_error($link) . "<br>";                  
     }
    
    //преобразование содержимого массива в строку
    $text_id = implode(mysqli_fetch_row($res));
    
    //заполнение таблицы word значением id загруженного текста, словами и количеством повторений
    foreach ($new_arr as $word => $count) {
        $query = "insert into word (text_id, word, count) values ('$text_id', '$word', '$count')";
        //вывод отчета
        if (mysqli_query($link, $query)) {                                      
            echo $word . " загружено в таблицу word<br>";
        }
         else {
            echo "ошибка загрузки анализа текста в таблицу uploaded_text: " . mysqli_error($link) . "<br>";                 
         }
    }
    
    //отключение от БД
    //вывод отчета
    if (mysqli_close($link)) {                                                                 
        echo "БД отключена<br>";
    }
     else {
        echo "ошибка отключения БД: " . mysqli_error($link) . "<br>";                 
     }
  
}
//ничего не произойдет, если текстовое поле пустое, вывод сообщения
 else {
    echo "текстовое поле не заполнено<br><br>";                                 
 }

?>

<form method="post" action="index.php" enctype="multipart/form-data">
    <button type="submit">вернуться</button>
</form>


