<?php
setcookie(session_name(), null, -1, '/');
session_destroy();
session_write_close();
header("location:index.php");