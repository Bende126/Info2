<?php
    include './check_login.php';
    if ($_SESSION['isadmin'] == true){
        session_destroy();
        shell_exec('mysql --host=localhost --user=labor --password=asdf1234 < "../database/init_db.sql"');
        $command = escapeshellcmd("../database/fill_db.py");
        shell_exec($command);
    }

    header('Location: ../../index.php')
?>