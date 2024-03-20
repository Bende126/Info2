<?php
    session_start();
    if( (!(isset($_SESSION['user'])) && !(isset($_SESSION['belepve'])) && ($_SESSION['belepve'] != true) ) )
    {
        session_destroy();
        header('Location: ../../index.php?error=Nincs bejelentkezve.');
    }
?>