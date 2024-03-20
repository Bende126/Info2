<?php
    include './scripts/php/check_login.php';
    session_start();
    session_destroy();
    header('Location: ./index.php');
?>