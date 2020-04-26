<?php

function debug($var) {
    echo '<pre>';
    if ($var) {
        print_r ($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}

function get_input_integer($source, $name) {
    $integer = filter_input($source, $name, FILTER_VALIDATE_INT);
    if (!$integer) {
        throw new Exception("параметр $name не число");
    }
    return $integer;
}

function get_input_url($source, $name) {
    $url = filter_input($source, $name, FILTER_VALIDATE_URL);
    if (!$url) {
        throw new Exception("параметр $name не URL");
    }
    return $url;
}