<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id){
        global $link;

        $query = "select * from jegy
        where ID = $id;";

        $sajat = mysqli_query($link, $query);

        if(mysqli_num_rows($sajat) !=0 ){
            closeDb($link);
            $error = "ID dupplikáció";
            header('Location: ../../../admin.php?error='.$error);
        }

        return;
    }

    function istableexists($id){
        global $link;
        
        $query = "select * from jegy_felhasznalo
        where JegyID = $id";

        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){
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

        $query = "select * from jegy
        where ID = $id;";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) == 0){
            closeDb($link);
            $error = "Nem létezik a megadott ID-jű elem";
            header('Location: ../../../admin.php?error='.$error);
        }
    }

    if(isset($_GET['a'])){
        $a = mysqli_real_escape_string($link, $_GET['a']);
        $query = "select * from jegy where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['Honnan'].' | '.$row['Hova'].' | '.$row['Utas'].' | '.$row['Ervenyes'].'';
        }
    }

    if(isset($_POST['ID_j']) && isset($_POST['Honnan_j']) && isset($_POST['Hova_j']) && isset($_POST['Utas_j']) && isset($_POST['Ervenyes_j'])){
        $rowexists = 'isrowexists';
        $tableexists = 'istableexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_j']);
        $honnan = mysqli_real_escape_string($link, $_POST['Honnan_j']);
        $hova = mysqli_real_escape_string($link, $_POST['Hova_j']);
        $utasid = mysqli_real_escape_string($link, $_POST['Utas_j']);
        $ervenyes = mysqli_real_escape_string($link, $_POST['Ervenyes_j']);

        if($honnan == "" || $hova == "" || $utasid == "" || $ervenyes == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        switch ($action) {
            case 'Create':
                /*ellenőrzini, hogy:
                1) ne legyen létező az ID
                2) itt nem kell dupplikáció miatt keresni, mert ugyanolyan jegyet többször is vehet az illető
                */

                $query = "insert into jegy(Honnan, Hova, Utas, Ervenyes) values ('".$honnan."','".$hova."', '".$utasid."', '".$ervenyes."');";
                if($id != ""){
                    $rowexists($id);
                    $query = "insert into jegy(ID, Honnan, Hova, Utas, Ervenyes) values ('".$id."','".$honnan."','".$hova."', '".$utasid."', '".$ervenyes."');";
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

                $query = "update jegy
                set Honnan = '$honnan', Hova = $hova, Utas = $utasid, Ervenyes = '$ervenyes'
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
                2) létezzen az id
                */
                $idcheck($id);

                $tableexists($id);

                $query = "delete from jegy where ID = '".$id."'";
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