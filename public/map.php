<?php
    include './header.php';
    $command = escapeshellcmd("./scripts/map/drawmap.py");
    $output = shell_exec($command);
    echo $output;
    include './footer.html';
?>