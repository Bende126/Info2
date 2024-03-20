<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $utvonalid, $vonatid){
        global $link;

        $query = "select * from vonat
        where ID = '".$vonatid."';";
        $vonat = mysqli_query($link, $query);

        $query = "select * from utvonal
        where ID = '".$utvonalid."';";
        $utvonal = mysqli_query($link, $query);

        $query= "select * from vonat_utvonal
        where ID = '".$id."';";
        $sajat = mysqli_query($link, $query);

        if(mysqli_num_rows($sajat) > 0){
            closeDb($link);
            $error = "Dupplikált ID";
            header('Location: ../../../admin.php?error='.$error);
        }

        if(mysqli_num_rows($vonat) == 0 || mysqli_num_rows($utvonal) == 0){
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

        $query = "select * from vonat_utvonal
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
        $query = "select * from vonat_utvonal where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['UtvonalID'].' | '.$row['VonatID'].' | ';
        }
    }

    if(isset($_POST['ID_vu']) && isset($_POST['UtvonalID_vu']) && isset($_POST['VonatID_vu'])){
        $func = 'isrowexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_vu']);
        $utvonalid = mysqli_real_escape_string($link, $_POST['UtvonalID_vu']);
        $vonatid = mysqli_real_escape_string($link, $_POST['VonatID_vu']);

        if($utvonalid == "" || $vonatid == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        switch ($action) {
            case 'Create':
                /*ellenőrzini, hogy:
                1) létezzen adat amire hivatkozik
                2) ne legyen létező az ID
                */

                $func($id = 0, $utvonalid, $vonatid);
                $query = "insert into vonat_utvonal(VonatID, UtvonalID) values ('".$vonatid."','".$utvonalid."');";

                if($id != ""){
                    $func($id, $utvonalid, $vonatid);
                    $query = "insert into vonat_utvonal(ID, VonatID, UtvonalID) values ('".$id."', '".$vonatid."','".$utvonalid."');";
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
                1) létezzen adat amire hivatkozik
                2) létezzen az id
                */
                $func($id, $utvonalid, $vonatid);
                $idcheck($id);

                if($id == ""){
                    closeDb($link);
                    $error = "Üres kötelező mezők";
                    header('Location: ../../../admin.php?error='.$error);
                }

                $query = "select * from jegy_utvonal
                where ID = $id;";
                $result = mysqli_query($link, $query);
                if(mysqli_num_rows($result) == 0){
                    closeDb($link);
                    $error = "Nem létezik a megadott ID-jű elem";
                    header('Location: ../../../admin.php?error='.$error);
                }

                $query = "update vonat_utvonal
                set UtvonalID = $utvonalid, VonatID = $vonatid
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
                /*ellenőrzini, hogy:
                1) létezzen az id
                */
                $idcheck($id);
                
                $query = "delete from vonat_utvonal where ID = '".$id."'";
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