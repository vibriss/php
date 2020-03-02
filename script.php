<?php

function text_analyse($str_input) {                                             //функция, в которой анализируется текст
    $str_lowered = mb_strtolower($str_input);					//преобразование в нижний регистр
    $str_trimmed = trim($str_lowered);						//удаление пробелов из начала и конца строки
    $arr = preg_split("/[\s,':\.]+/", $str_trimmed, null, PREG_SPLIT_NO_EMPTY);	//разделение строки на отдельные слова
    $new_arr = array_count_values($arr);                                        //заполнение ассоциативного массива, ключами которого являются слова, а значениями - количество повторений
    return [$new_arr, $arr];                                                    //вывод результатов функции
}

$i = 1;
$str_input_text = $_GET["text"];                                                //запрос к текстовому полю
if ($str_input_text != null) {                                                  //если текстовое поле не пустое, выполняется следующее:
    echo "текстовое поле: <br>", $str_input_text, "<br><br>";                   //вывод текстового поля
    $file = "text_out" . $i . ".csv";                                           //присваивание имени "text_out1.csv" файлу с отчетом
    while (file_exists($file)) {                                                //если такой файл уже существует
        $file = "text_out" . $i . ".csv";                                       //проверять далее и индексом +1
        $i++;           
    }
    $f = fopen($file, "w+");                                                    //открытие файла отчета для записи
    list($new_arr, $arr) = text_analyse($str_input_text);                       //присвоение массивам результатов вызова функции
    foreach ($new_arr as $word => $count) {                                     //перебор значений массива
        fwrite($f, $word . " " . $count . "\r\n");                              //запись значений в файл
    }
    fwrite($f, "всего слов " . count($arr));                                    //записть общего количества слов в файл
    fclose($f);                                                                 //закрытие файла
    echo "результат записан в ", $file, "<br><br>";
}
 else {
    echo "текстовое поле не заполнено<br><br>";                                 //если текстовое поле пустое, вывод сообщения
 }

$i = 1;
if ($_GET["file"] != null) {                                                    //запрос к файловому полю, если файл загружен
    $str_input_file = file_get_contents($_GET["file"]);                         //считывание содержания файла в строку
    if ($str_input_file != null) {                                              //если строка не пустая, выполняется следующее:
        echo "текст в файле: <br>", $str_input_file, "<br><br>";                //вывод содержимого файла
        $file = "file_out" . $i . ".csv";                                       //присваивание имени "file_out1.csv" файлу с отчетом
        while (file_exists($file)) {                                            //если такой файл уже существует
            $file = "file_out" . $i . ".csv";                                   //проверять далее и индексом +1
            $i++;
        }
        $f = fopen($file, "w+");                                                //открытие файла отчета для записи
        list($new_arr, $arr) = text_analyse($str_input_file);                   //присвоение массивам результатов вызова функции
        foreach ($new_arr as $word => $count) {                                 //перебор значений массива
            fwrite($f, $word . " " . $count . "\r\n");                          //запись значений в файл
        }
        fwrite($f, "всего слов " . count($arr));                                //записть общего количества слов в файл
        fclose($f);                                                             //закрытие файла
        echo "результат записан в ", $file, "<br><br>";
    }
}
 else {
    echo "файл отсутствует или пустой<br><br>";                                 //если файл не загружен или пустой, вывод сообщения
 }