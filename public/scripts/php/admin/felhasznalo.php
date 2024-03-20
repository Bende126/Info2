<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $nev, $username, $telefon, $email){
        global $link;

        $query = "select * from felhasznalo
        where Nev = '".$nev."' and Telefon = '".$telefon."' and Email = ".$email." and UserName = '".$username."';";

        $result = mysqli_query($link, $query);

        $query = "select * from felhasznalo
        where ID = $id;";

        $sajat = mysqli_query($link, $query);

        if(mysqli_num_rows($sajat) !=0 ){
            closeDb($link);
            $error = "ID dupplikáció";
            header('Location: ../../../admin.php?error='.$error);
        }

        if(mysqli_num_rows($result) > 0){
            closeDb($link);
            $error = "Dupplikáció";
            header('Location: ../../../admin.php?error='.$error);
        }

        return;
    }

    function istableexists($id){
        global $link;
        
        $query = "select * from jegy_felhasznalo
        where FelhasznaloID = $id;";

        $result1 = mysqli_query($link, $query);

        $query = "select * from jegy
        where Utas = $id;";

        $result2 = mysqli_query($link, $query);

        if(mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0){
            closeDb($link);
            $error = "Kapcsolótáblában szereplő adat, nem lehet törölni";
            header('Location: ../../../admin.php?error='.$error);
        }

        return;
    }

    function idcheck($id){
        global $link;

        if($id == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        $query = "select * from felhasznalo
        where ID = $id;";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) == 0){
            closeDb($link);
            $error = "Nem létezik a megadott ID-jű elem";
            header('Location: ../../../admin.php?error='.$error);
        }
    }

    function createadmin($isadmin){
        if(strtolower($isadmin) == "true" || strtolower($isadmin) == "false"){
            return ((strtolower($isadmin) == 'true') ? 'true' : 'false');
        }
        return 'false';
    }

    function checkstyle($rawstyle){
        $files = scandir('../../../imgs');
        unset($files[0]); // .
        unset($files[1]); // ..
        $izero = array_values($files); //helyreállítja a tömb számozását
        foreach ($izero as $key => $value) {
            $trimmed = trim($value, '.png');
            if($trimmed == $rawstyle){
                return $rawstyle;
            }
        }
        return 'default';
    }

    if(isset($_GET['a'])){
        $a = mysqli_real_escape_string($link, $_GET['a']);
        $query = "select * from felhasznalo where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $admin = ($row['IsAdmin'] == '1' ? 'True' : 'False');
            echo''.$row['ID'].' | '.$row['Nev'].' | '.$row['Telefon'].' | '.$row['Email'].' | '.$row['UserName'].' | '.$row['Passwd'].' | '.$admin.' | '.$row['Style'].'';
        }
    }

    if(isset($_POST['ID_f']) && isset($_POST['Nev_f']) && isset($_POST['Telefon_f']) && isset($_POST['Email_f']) && isset($_POST['UserName_f']) && isset($_POST['Passwd_f']) && isset($_POST['IsAdmin_f']) && isset($_POST['Style_f'])){
        $rowexists = 'isrowexists';
        $tableexists = 'istableexists';
        $idcheck = 'idcheck';
        $createadmin = 'createadmin';
        $createstyle = 'checkstyle';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_f']);
        $nev = mysqli_real_escape_string($link, $_POST['Nev_f']);
        $telefon = mysqli_real_escape_string($link, $_POST['Telefon_f']);
        $email = mysqli_real_escape_string($link, $_POST['Email_f']);
        $username = mysqli_real_escape_string($link, $_POST['UserName_f']);
        $password = hash('md5',mysqli_real_escape_string($link, $_POST['Passwd_f']));
        $isadmin = mysqli_real_escape_string($link, $_POST['IsAdmin_f']);
        $raw_style = mysqli_real_escape_string($link, $_POST['Style_f']);

        $admin = $createadmin($isadmin);
        $style = $createstyle($raw_style);

        if($nev == "" || $username == "" || $password == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        if($id == $_SESSION['user']){
            closeDb($link);
            $error = "Saját profilt nem lehet szerkeszteni";
            header('Location: ../../../admin.php?error='.$error);
        }

        switch ($action) {
            case 'Create':
                /*ellenőrzini, hogy:
                1) ne legyen létező az ID
                2) ne legyen 1-1 ugyanaz az insert
                */
                $rowexists($id = 0, $nev, $username, $telefon, $email);
                $query = "insert into felhasznalo(Nev, UserName, Telefon, Email, Passwd, IsAdmin, Style) values ('".$nev."','".$username."', '".$telefon."', '".$email."', '".$password."', '".$admin."', '".$style."');";

                if($id != ""){
                    $rowexists($id, $nev, $username, $telefon, $email);
                    $query = "insert into felhasznalo(ID, Nev, UserName, Telefon, Email, Passwd, IsAdmin, Style) values ('".$id."', '".$nev."','".$username."', '".$telefon."', '".$email."', '".$password."', '".$isadmin."', '".$style."');";
                }
                if(!mysqli_query($link, $query)){
                    closeDb($link);
                    header('Location: ../../../admin.php?error='.mysqli_error($link));
                }
                closeDb($link);
                header('Location: ../../../admin.php');
                break;
            
            case 'Edit':
                /*ellenőrzini, hogy:
                1) létezzen az id
                */
                $idcheck($id);

                $query = "update felhasznalo
                set Nev = '$nev', Telefon = '$telefon', Email = '$email', UserName = '$username', Passwd = '$password', IsAdmin = $admin, Style = '$style'
                where ID = $id;";
                echo $query;
                if(!mysqli_query($link, $query)){
                    closeDb($link);
                    header('Location: ../../../admin.php?error='.mysqli_error($link));
                }
                closeDb($link);
                header('Location: ../../../admin.php');
                break;

            case 'Delete':
                /*ellenőrizni, hogy:
                1) van-e kapcsolótábla, ami erre az adatra hivatkozik
                2) létezik-e az id
                */
                $tableexists($id);
                $idcheck($id);

                $query = "delete from felhasznalo where ID = '".$id."'";
                if(!mysqli_query($link, $query)){
                    closeDb($link);
                    header('Location: ../../../admin.php?error='.mysqli_error($link));
                }
                closeDb($link);
                header('Location: ../../../admin.php');
                break;
        }
    }
?>