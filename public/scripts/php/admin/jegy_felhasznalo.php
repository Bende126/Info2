<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $jegyid,  $felhasznaloid){
        global $link;

        $query = "select * from jegy_felhasznalo
        where ID = $id;";

        $sajat = mysqli_query($link, $query);

        if(mysqli_num_rows($sajat) !=0 ){
            closeDb($link);
            $error = "ID dupplikáció";
            header('Location: ../../../admin.php?error='.$error);
        }

        $query = "select * from jegy
        where ID = $jegyid;";

        $jegy = mysqli_query($link, $query);

        $query = "select * from felhasznalo
        where ID =$felhasznaloid;";

        $felhasznalo = mysqli_query($link, $query);

        if(mysqli_num_rows($jegy) == 0 || mysqli_num_rows($felhasznalo) == 0){
            closeDb($link);
            $error = "Nem létező adat";
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

        $query = "select * from jegy_felhasznalo
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
        $query = "select * from jegy_felhasznalo where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['JegyID'].' | '.$row['FelhasznaloID'].'';
        }
    }

    if(isset($_POST['ID_jf']) && isset($_POST['JegyID_jf']) && isset($_POST['FelhasznaloID_jf'])){
        $rowexists = 'isrowexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_jf']);
        $jegyid = mysqli_real_escape_string($link, $_POST['JegyID_jf']);
        $felhasznaloid = mysqli_real_escape_string($link, $_POST['FelhasznaloID_jf']);

        if($jegyid == "" || $felhasznaloid == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        switch ($action) {
            case 'Create':
                /*ellenőrzini, hogy:
                1) ne legyen létező az ID
                2) értelmes adatra mutasson a kapcsolótábla
                */

                $rowexists($id = 0, $jegyid, $felhasznaloid);
                $query = "insert into jegy_felhasznalo(JegyID, FelhasznaloID) values ('".$jegyid."','".$felhasznaloid."');";

                if($id != ""){
                    $rowexists($id, $jegyid, $felhasznaloid);
                    $query = "insert into jegy_felhasznalo(ID, JegyID, FelhasznaloID) values ('".$id."', '".$jegyid."','".$felhasznaloid."');";
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
                1) értelmes adatra mutasson a kapcsolótábla
                2) létezzen az id
                */
                $rowexists($id, $jegyid, $felhasznaloid);
                $idcheck($id);


                $query = "update jegy_felhasznalo
                set JegyID = $jegyid, FelhasznaloID = $felhasznaloid
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
                1) létezzen az id
                */
                $idcheck($id);

                $query = "delete from jegy_felhasznalo where ID = '".$id."'";
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