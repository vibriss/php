<?php
function show_login_form($errors = []) {
    echo '<form method="POST" enctype="multipart/form-data">
             логин <input name="login" type="text"><br>
             пароль <input name="password" type="password"><br>
             <button type="submit" name ="submit_login">войти</button><br>
          </form>';
    if (isset($errors) && !empty($errors)) {
        echo implode('<br/>', $errors)."<br/>";
    }
}

function show_logout_form() {
    echo '<form method="POST" action="logout.php" enctype="multipart/form-data">
             <button type="submit" name="submit_logout">выйти</button>
          </form>';
}

function show_registration_form($errors = []) {
    echo '<form method="POST" enctype="multipart/form-data">
             логин <input name="login" type="text"><br>
             пароль <input name="password" type="password"><br>
             <button type="submit" name ="submit_registration">зарегистрироваться</button><br>
          </form>';
    
    if (isset($errors) && !empty($errors)) {
        echo implode('<br/>', $errors)."<br/>";
    }
}

function show_upload_form($errors = []) {
    echo '<form method="POST" enctype="multipart/form-data">
             <input type="file" name="file">
             <button type="submit" name="submit_upload">загрузить</button>
          </form>';
    
    if (isset($errors) && !empty($errors)) {
        echo implode('<br/>', $errors)."<br/>";
    }
}

function show_gallery($gallery_data) {
    echo '<table border="1" cellspacing="20" cellpadding="10"><tbody><tr>';
    foreach ($gallery_data as $key => $image) {
        $image['path'] = 'img\\' . $image['name'];
    
        echo '<td valign = "top">';
        echo '<a href="show_image.php?image=' . $image['img_id'] . '" target="_blank"><img src="' . $image['path'] . '" width="200"></a><br>';
        echo 'просмотров : ' . $image['count'];
        echo '</td>';
    
        if (($key + 1) % 4 == 0) {
            echo '</tr><tr>';
        }
    }
    echo '</tr></tbody></table>';
}

function show_my_gallery($gallery_data) {
    if (!count($gallery_data)) {
        return print 'в этой галерее пока ничего нет';
    }
    
    echo '<form method="POST" enctype="multipart/form-data">';
    echo '<table border="1" cellspacing="20" cellpadding="10"><tbody><tr>';
    foreach ($gallery_data as $key => $image) {
        $image['path'] = 'img\\' . $image['name'];
    
        echo '<td valign = "top">';
        echo '<a href="show_image.php?image=' . $image['img_id'] . '" target="_blank"><img src="' . $image['path'] . '" width="200"></a></br>';
        echo '<div>просмотров : ' . $image['count'] . '</div>';
        echo '<input type="checkbox" name="img_id_to_delete[]" value="' . $image['img_id'] . '">';
        echo '</td>';
    
        if (($key + 1) % 4 == 0) {
            echo '</tr><tr>';
        }
    }
    echo '</tr></tbody></table>';
    echo '<button type="submit" name="submit_delete">удалить</button>';
    echo '</form>';
}