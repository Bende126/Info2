<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $nev, $pointa, $pointb){
        global $link;

        $query = "select * from utvonal
        where Ut_nev = '$nev' and PointA = $pointa and PointB = ".$pointb.";";

        $result = mysqli_query($link, $query);

        $query = "select * from utvonal
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
        
        $query = "select * from vonat_utvonal
        where UtvonalID = $id;";

        $result1 = mysqli_query($link, $query);

        $query = "select * from allomas_utvonal
        where UtvonalID = $id;";

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

        $query = "select * from utvonal
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
        $query = "select * from utvonal where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['Ut_nev'].' | '.$row['PointA'].' | '.$row['PointB'].'';
        }
    }

    if(isset($_POST['ID_u']) && isset($_POST['Ut_nev_u']) && isset($_POST['PointA_u']) && isset($_POST['PointB_u'])){
        $rowexists = 'isrowexists';
        $tableexists = 'istableexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_u']);
        $nev = mysqli_real_escape_string($link, $_POST['Ut_nev_u']);
        $pointa = mysqli_real_escape_string($link, $_POST['PointA_u']);
        $pointb = mysqli_real_escape_string($link, $_POST['PointB_u']);

        if($nev == "" || $pointa == "" || $pointb == ""){
            closeDb($link);
            $error = "Üres kötelező mezők";
            header('Location: ../../../admin.php?error='.$error);
        }

        switch ($action) {
            case 'Create':
                /*ellenőrzini, hogy:
                1) ne legyen létező az ID
                2) ne legyen 1-1 ugyanaz az insert
                */
                $rowexists($id = 0,$nev,  $pointa, $pointb);
                $query = "insert into utvonal(Ut_nev, PointA, PointB) values ('".$nev."','".$pointa."', '".$pointb."');";
                if($id != ""){
                    $rowexists($id, $nev, $pointa, $pointb);
                    $query = "insert into utvonal(ID, Ut_nev, PointA, PointB) values ('".$id."','".$nev."','".$pointa."', '".$pointb."');";
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

                $query = "update utvonal
                set Ut_nev = '$nev', PointA = $pointa, PointB = $pointb
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
                $tableexists($id);
                $idcheck($id);

                $query = "delete from utvonal where ID = '".$id."'";
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