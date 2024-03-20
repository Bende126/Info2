<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $allomasid,  $utvonalid){
        global $link;

        $query = "select * from allomas_utvonal
        where ID = $id;";

        $sajat = mysqli_query($link, $query);

        if(mysqli_num_rows($sajat) !=0 ){
            closeDb($link);
            $error = "ID dupplikáció";
            header('Location: ../../../admin.php?error='.$error);
        }

        $query = "select * from allomas
        where ID = $allomasid;";

        $jegy = mysqli_query($link, $query);

        $query = "select * from utvonal
        where ID =$utvonalid;";

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

        $query = "select * from allomas_utvonal
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
        $query = "select * from allomas_utvonal where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['AllomasID'].' | '.$row['UtvonalID'].'';
        }
    }

    if(isset($_POST['ID_au']) && isset($_POST['AllomasID_au']) && isset($_POST['UtvonalID_au'])){
        $rowexists = 'isrowexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_au']);
        $allomasid = mysqli_real_escape_string($link, $_POST['AllomasID_au']);
        $utvonalid = mysqli_real_escape_string($link, $_POST['UtvonalID_au']);

        if($allomasid == "" || $utvonalid == ""){
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
                $rowexists($id ,$allomasid, $utvonalid);
                $query = "insert into allomas_utvonal(AllomasID, UtvonalID) values ('".$allomasid."','".$utvonalid."');";

                if($id != ""){
                    $rowexists($id, $jegyid, $felhasznaloid);
                    $query = "insert into allomas_utvonal(ID, AllomasID, UtvonalID) values ('".$id."', '".$allomasid."','".$utvonalid."');";
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
                $rowexists($id, $allomasid, $utvonalid);
                $idcheck($id);

                $query = "update allomas_utvonal
                set AllomasID = $allomasid, UtvonalID = $utvonalid
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

                $query = "delete from allomas_utvonal where ID = '".$id."'";
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