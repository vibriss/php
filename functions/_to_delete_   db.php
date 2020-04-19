<?php
$PDO = null;

function connect() {
    global $PDO;
    if (isset($PDO)) {
        return $PDO;
    }
    require 'config/mysql.php';       
    $dsn = "mysql:host=$host;dbname=$dbname";
    $opt = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ];
    $PDO = new PDO($dsn, $login, $password, $opt);                    
    return $PDO;
}

function query($statement, $input_array) {
    $pdo = connect();
    $result = $pdo->prepare($statement);
    $result->execute($input_array);
    return $result;
}

function increment_img_counter($id) {
    return query ('UPDATE images SET count = count + 1 WHERE img_id = ?', [$id]);
}

function get_img_path_by_id($id) {
    $result = query ('SELECT name FROM images WHERE img_id = ?', [$id]);
    $result_array = $result->fetch();
    if ($result_array) {
        return 'img\\' . $result_array['name'];
    } else {
        return null;
    }
}

function get_img_name_by_id($id) {
    $result = query ('SELECT name FROM images WHERE img_id = ?', [$id]);
    $result_array = $result->fetch();
    if ($result_array) {
        return $result_array['name'];
    } else {
        return null;
    }
}

function get_gallery_data($img_count = 12, $random = false) {
    $query_string = 'SELECT img_id, name, count FROM images ';
    if ($random) {
        $query_string .= 'ORDER BY rand() ';
    }
    $query_string .= 'LIMIT ' . $img_count;
    
    $result = query($query_string, null);
    return $result->fetchall();
}

function get_user_gallery_data($login) {
    $result = query('SELECT img_id, name, count FROM images JOIN users ON (images.user_id = users.user_id) WHERE login = ?', [$login]);
    return $result->fetchall();
}

function get_user_id_by_login($login) {
    $result = query('SELECT user_id FROM users WHERE login = ?', [$login]);
    $result_array = $result->fetch();
    return $result_array['user_id'];
}

function login_exists_in_db($login) {
    $result = query ('SELECT count(login) AS exist FROM users WHERE login = ?', [$login]);
    $result_array = $result->fetch();
    return $result_array['exist'] == 1;
}

function password_match_login($login, $password) {
    $result = query ('SELECT login, password FROM users WHERE login = ?', [$login]);
    $result_array = $result->fetch();
    if ($result_array) {
        return password_verify($password, $result_array['password']);
    } else {
        return false;
    }
}

function generate_next_filename() {
    return uniqid() . '.jpg';
}

/*
function generate_next_filename($login) {
    $user_id = get_user_id_by_login($login);
    $result = query('SELECT max(img_id) AS img_id FROM images WHERE user_id = ?', [$user_id]);
    $result_array = $result->fetch();
    $sequence = $result_array['img_id'] + 1;
    return $user_id . "_" . $sequence . "_img.jpg";
}  
*/

function add_filename_to_user_gallery($file_name, $login) {
    return query('INSERT into images (user_id, name) values (:user_id, :name)',
           ['user_id' => get_user_id_by_login($login), 'name' => $file_name]);
}

function delete_image_by_id($login, $img_id) {
    $result = query ('SELECT user_id FROM images WHERE img_id = ?', [$img_id]);
    $result_array = $result->fetch();
    if ($result_array['user_id'] != get_user_id_by_login($login)) {
        return ['success' => false, 'errors' => ['нельзя удалять чужие фото']];
    } else {
        $image_path = get_img_path_by_id($img_id);
        query ('DELETE FROM images WHERE img_id = ?', [$img_id]);
        unlink($image_path);
        return ['success' => true];
    }
}