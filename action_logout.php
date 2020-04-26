<?php
setcookie(session_name(), null, -1, '/');
session_destroy();
header("location:index.php");