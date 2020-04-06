<?php
require_once 'functions_db.php';
require_once 'functions_check.php';
require_once 'forms.php';

function upload($login, $file) {
    $result = file_check($file);
    if (!$result['success']) {
        return $result;
    }
    
    $file_name = generate_next_filename($login);
    move_uploaded_file($file['tmp_name'], __DIR__ . '\\img\\' . $file_name);
    query('INSERT into images (user_id, name) values (:user_id, :name)',
          ['user_id' => get_user_id_by_login($login), 'name' => $file_name]);
    return ['success' => true, 'errors' => []];
}

session_start();

if (!isset($_SESSION['login'])) {
    header("refresh: 3; url=index.php");
    echo 'сначала нужно войти';
    exit();
}

$attempt_upload_result = ['success' => false, 'errors' => []];
if (!empty($_POST) && isset($_POST['submit_upload'])) {
    $attempt_upload_result = upload($_SESSION['login'], $_FILES['file']);
}

echo '<a href="index.php">вернуться</a>';
show_upload_form($attempt_upload_result['errors']);
if ($attempt_upload_result['success']) {
    echo 'файл загружен';
}


/*

//если нажата кнопка "загрузить"
if(isset($_POST['submit_upload'])) {  
    //проверка файла
    if (check_file($_FILES['file'])) {
        //если проверка пройдена, подготавливается имя для записи в бд в виде 'user_id'_'img_id'_img.jpg
        //первая часть имени - user_id
        $result = query('SELECT user_id FROM users WHERE login = ?', array($_SESSION['login']));
        $result_array = $result->fetch();
        $first_part = implode($result_array);
        //вторая часть имени - img_id     
        $result = query('SELECT max(img_id) FROM images', null);
        $result_array = $result->fetch();
        //на случай, если в базе нет записей
        if (implode($result_array) == null) {
            $second_part = 1;
        }
         else {
            $second_part = implode($result_array) + 1; 
         }
        //формирование имени файла        
        $image_name = $first_part . "_" . $second_part . "_img.jpg";
        $file = $_FILES['file'];
        //загрузка файла
        if (move_uploaded_file($file['tmp_name'], "$upload_dir/$image_name") == false) {
            echo 'файл не загружен<br>';
        }
         //если файл загружен, внести его имя в бд
         else {
            echo 'файл загружен под именем ' . $image_name . '<br>';
            $user_id = $first_part;
            $result = query ('INSERT into images (user_id, name) values (:user_id, :name)',
                             array(':user_id'=>$user_id, ':name'=>$image_name));
         }
    }    
}

/*$result = query ('SELECT user_id FROM users WHERE login = ?',
                 array($_SESSION['login']));
$result_array = $result->fetch();


//получение имен всех файлов, загруженных текущим пользователем
$result = query ('SELECT image_name FROM images WHERE user_id = :user_id',
                 $result_array); 

$result = query ('SELECT name FROM images JOIN users ON (images.user_id = users.user_id) WHERE login = ?',
                 array($_SESSION['login']));
$result_array = $result->fetchall();

?>

<form method="POST" enctype="multipart/form-data">
<select name="select">
    
<?php
//формирование опционального выбора для удаления файлов
foreach ($result_array as $image_name_array) {
    $image_name = $image_name_array['name'];
    ?>
    <option value="<?php echo $image_name ?>"><?php echo $image_name; ?></option>
    <?php
}
?>
    
</select>
<button type="submit" name="submit_delete">удалить</button>
</form>

<?php

//если нажата кнопка удалить
if(isset($_POST['submit_delete'])) {
    $image_name = $_POST['select'];
    $image_path = "img\\" . $_POST['select'];
    //удаление записи из бд
    $result = query ('DELETE FROM images WHERE name = ?',
                     array($image_name));
    //удаление файла
    unlink($image_path);
    header("Refresh:0");
      
}
?>

<table border="1" cellspacing="20" cellpadding="10">  
    <tbody>
        <tr>
    
<?php
$number_of_row = 0;
//вывод всех картинок пользователя в виде таблицы из трех колонок
foreach ($result_array as $image_name_array) {
    $image_name = $image_name_array['name'];
    $image_path = "img\\" . $image_name;
    if ($number_of_row < 3) {
        ?>
        <td>
            <?php
            echo '<img src="' . $image_path . '"width="200">';
            echo "<br>" . $image_name;
            ?>
        </td>
        <?php
    }
     else {
        ?>
        </tr>
        <tr>
        <td>
            <?php
            $number_of_row = 0;
            echo '<img src="' . $image_path . '"width="200">';
            echo "<br>" . $image_name;
            ?>
        </td>
        <?php
     }
    $number_of_row++;
}
?>
        </tr>
    </tbody>
</table>

*/