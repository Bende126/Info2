<?php
include_once './db.php';
$link = getDb();

if (!isset($_POST['username']) || !isset($_POST['password'])){
    $error = "POST error /:";
    header('Location: ../../index.php?error='.$error);
}

$username = mysqli_real_escape_string($link,$_POST['username']);
$password = hash('md5',mysqli_real_escape_string($link,$_POST['password']));

$query = "select * from felhasznalo where UserName='".$username."';";
$result = mysqli_query($link,$query);

if($user = mysqli_fetch_assoc($result)){
    if ($user['Passwd'] === $password){
        session_start();
        $_SESSION['user'] = $user['ID'];
        $_SESSION['belepve'] = true;
        $_SESSION['isadmin'] = false;
        $_SESSION['username'] = $username;
        $_SESSION['style'] = $user['Style'];

        if($user['IsAdmin']==1){
            $_SESSION['isadmin'] = true;
        }

        closeDb($link);
        header('Location: ../../index.php');
    }
    else {
        $error = "Rossz jelszó";
        header('Location: ../../login_screen.php?username='.$username.'&error='.$error);
    }
}
else {
    $error = "Nincs ilyen felhasználó";
    header('Location: ../../login_screen.php?username='.$username.'&error='.$error);
}
?>