<?php
    include './db.php';
    session_start();

    $link = getDb();

    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = hash('md5',mysqli_real_escape_string($link,$_POST['password']));
    $utas = mysqli_real_escape_string($link, $_POST['utas']);

    $query = "insert into felhasznalo(Nev, UserName, Passwd) values ('".$utas."', '".$username."', '".$password."');";

    if ($_POST['telefon'] != ""){
        $telefon = mysqli_real_escape_string($link, $_POST['telefon']);
        $query = "insert into felhasznalo(Nev, UserName, Passwd, Telefon) values ('".$utas."', '".$username."', '".$password."', '".$telefon."');";
    }

    if ($_POST['email'] != ""){
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $query = "insert into felhasznalo(Nev, UserName, Passwd, Email) values ('".$utas."', '".$username."', '".$password."', '".$email."');";
    }

    if($_POST['telefon'] !="" && $_POST['email'] != ""){
        $query = "insert into felhasznalo(Nev, UserName, Passwd, Email, Telefon) values ('".$utas."', '".$username."', '".$password."', '".$email."', '".$telefon."');";
    }

    $result = mysqli_query($link, $query);

    if(!$result){
        $error = "Adatbázis insert hiba:".mysqli_errno($link).":/";
        header('Location: ../../login_screen.php?error =' .$error);
    }

    $query = "select * from felhasznalo where UserName='".$username."';";
    $result = mysqli_query($link,$query);

    $user = mysqli_fetch_assoc($result);
    
    $_SESSION['user'] = $user['ID'];
    $_SESSION['belepve'] = true;
    $_SESSION['isadmin'] = false;
    $_SESSION['username'] = $username;
    $_SESSION['style'] = $user['Style'];

    closeDb($link);

    header("Location: ../../index.php");

?>