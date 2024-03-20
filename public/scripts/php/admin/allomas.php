<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $nev, $coorda, $coordb){
        global $link;

        $query = "select * from allomas
        where Ut_nev = '$nev' and PointA = $coorda and PointB = ".$coordb.";";

        $result = mysqli_query($link, $query);

        $query = "select * from allomas
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
        
        $query = "select * from utvonal
        where PointA = $id or PointB = $id;";

        $result1 = mysqli_query($link, $query);

        $query = "select * from jegy
        where Honnan = $id or Hova = $id;";

        $result2 = mysqli_query($link, $query);

        $query = "select * from allomas_utvonal
        where AllomasID = $id;";

        $result3 = mysqli_query($link, $query);

        if(mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0 || mysqli_num_rows($result3) > 0){
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

        $query = "select * from allomas
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
        $query = "select * from allomas where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['Nev'].' | '.$row['Coord_x'].' | '.$row['Coord_y'].'';
        }
    }

    if(isset($_POST['ID_a']) && isset($_POST['Nev_a']) && isset($_POST['Coord_x_a']) && isset($_POST['Coord_y_a'])){
        $rowexists = 'isrowexists';
        $tableexists = 'istableexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_a']);
        $nev = mysqli_real_escape_string($link, $_POST['Nev_a']);
        $coorda = mysqli_real_escape_string($link, $_POST['Coord_x_a']);
        $coordb = mysqli_real_escape_string($link, $_POST['Coord_y_a']);

        if($nev == "" || $coorda == "" || $coordb == ""){
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
                $rowexists($id = 0,  $nev, $coorda, $coordb);
                $query = "insert into allomas(Nev, Coord_x, Coord_y) values ('".$nev."','".$coorda."', '".$coordb."');";

                if($id != ""){
                    $rowexists($id, $nev, $coorda, $coordb);
                    $query = "insert into allomas(ID, Nev, Coord_x, Coord_y) values ('".$id."', '".$nev."','".$coorda."', '".$coordb."');";
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

                $query = "update allomas
                set Nev = '$nev', Coord_x = $coorda, Coord_y = $coordb
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