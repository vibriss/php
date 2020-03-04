<?php

function text_analyse($str_input) {                                             //функция, в которой анализируется текст
    $str_lowered = mb_strtolower($str_input);					//преобразование в нижний регистр
    $str_trimmed = trim($str_lowered);						//удаление пробелов из начала и конца строки
    $arr = preg_split("/[\s,':\.]+/", $str_trimmed, null, PREG_SPLIT_NO_EMPTY);	//разделение строки на отдельные слова
    $new_arr = array_count_values($arr);                                        //заполнение ассоциативного массива, ключами которого являются слова, а значениями - количество повторений
    return [$new_arr, $arr];                                                    //вывод результатов функции
}
    
function output_to_csv($file, $str_input) {                                     //функция, в которой осуществляется запись в файл
    $f = fopen("./csvs/" . $file, "w+");                                        //открытие файла для записи отчета
    list($new_arr, $arr) = text_analyse($str_input);                            //присвоение массивам результатов вызова функции
    foreach ($new_arr as $word => $count) {                                     //перебор значений массива
        $arr_to_csv = array ($word, $count);                                    //создание массива для записи строки в .csv
        fputcsv($f, $arr_to_csv, " ");                                          //запись в .csv файл
    }
    fputcsv($f, array("всего", "слов", count($arr)), " ");                      //запись общего количества слов в файл
    fclose($f);                                                                 //закрытие файла
    echo "результат записан в ", $file, "<br><br>";
}

$str_input = $_POST["text"];                                                    //запрос к текстовому полю
if ($str_input != null) {                                                       //если текстовое поле не пустое, выполняется следующее:
    echo "текстовое поле: <br>", $str_input, "<br><br>";                        //вывод текста
    $file = date("dMY_H.i.s") . "_" . md5($str_input) . ".csv";                 //присваивание имени файлу, состоящего из даты и хэша текста
    output_to_csv($file, $str_input);                                           //вызов функции записи в файл
}
 else {
    echo "текстовое поле не заполнено<br><br>";                                 //если текстовое поле пустое, вывод сообщения
 }

if ($_FILES["uploaded_file"]["error"] === UPLOAD_ERR_OK) {                      //запрос к файловому полю, если файл загружен без ошибок
    $str_input = file_get_contents($_FILES["uploaded_file"]["name"]);           //считывание содержания файла в строку
    if ($str_input != null) {                                                   //если строка не пустая, выполняется следующее:
        echo "текст в файле: <br>", $str_input, "<br><br>";                     //вывод содержимого файла
        $file = date("dMY_H.i.s") . "_" . md5($str_input) . ".csv";             //присваивание имени файлу, состоящего из даты и хэша текста
        output_to_csv($file, $str_input);                                       //вызов функции записи в файл
    }
}
 else {
    echo "файл не прикреплен или пустой<br><br>";                               //если файл не загружен или пустой, вывод сообщения
 }
