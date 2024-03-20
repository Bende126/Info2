<?php
include './check_login.php';
include './db.php';
$link = getDb();

if(isset($_GET['a'])){
    $a = mysqli_real_escape_string($link, $_GET['a']);
    $trima = trim($a, '.png');

    $query = "update felhasznalo
    set Style = '".$trima."'
    where ID = '".$_SESSION['user']."';";

    mysqli_query($link, $query);

    $query = "select * from felhasznalo
    where ID = '".$_SESSION['user']."';";

    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['style'] = $user['Style'];

    closeDb($link);
    header('Location: ../../index.php');
}
?>