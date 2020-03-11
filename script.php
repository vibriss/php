<?php
//подключение файла с функцией connect
require "index.php";

//функция, в которой анализируется текст
function text_analyse($str_input) {
    //очистка строки от символов
    $str_cleaned = preg_replace('/[^ a-zа-яё\d]/ui', '', $str_input);
    //преобразование в нижний регистр
    $str_lowered = mb_strtolower($str_cleaned);					
    //удаление пробелов из начала и конца строки
    $str_trimmed = trim($str_lowered);						
    //разделение строки на отдельные слова
    $arr = preg_split("/ /", $str_trimmed, null, PREG_SPLIT_NO_EMPTY);	
    //заполнение ассоциативного массива, ключами которого являются слова, а значениями - количество повторений
    $new_arr = array_count_values($arr);                                        
    //вывод результатов функции
    return [$new_arr, $arr];                                                    
}

//запрос к текстовому полю формы
$str_input = $_POST["text"];                                                    

//если текстовое поле не пустое, выполняется следующее:
if ($str_input != null) {                                                       
    //вызов функции подключения(описана в index.php)
    $pdo = connect();
    //вызов функции анализа текста
    list($new_arr, $arr) = text_analyse($str_input);
    //подсчет количества слов
    $words_count = count($arr);
    //подготовка выражения для заполнения таблицы uploaded_text содержанием текстового поля, текущей датой, количеством слов в тексте
    $res = $pdo->prepare('INSERT into uploaded_text (content, date, words_count) values (:content, now(), :words_count)');
    //выполнение
    $res->execute(array(':content'=>$str_input, ':words_count'=>$words_count));
    //подготовка выражения для выбора значения id из таблицы uploaded_text для последнего поля content(загруженный текст), совпадающего с новым введенным текстом
    $res = $pdo->prepare('SELECT id FROM uploaded_text WHERE content = ? order by id desc');
    //выполнение
    $res->execute(array($str_input));
    //получение массива с результатом
    $res_arr = $res->fetch();
    //преобразование содержимого массива в строку
    $text_id = implode($res_arr);
    //заполнение таблицы word значением id загруженного текста, словами и количеством повторений
    foreach ($new_arr as $word => $count) {
        //подготовка выражения
        $res = $pdo->prepare('INSERT into word (text_id, word, count) values (:text_id, :word, :count)');
        //выполнение
        $res->execute(array(':text_id'=>$text_id, ':word'=>$word, ':count'=>$count));
    }
}
?>