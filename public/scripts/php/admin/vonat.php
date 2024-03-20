<?php 
    include '../db.php';
    $link = getDb();

    function isrowexists($id, $nev, $kapacitas){
        global $link;

        $query = "select * from vonat
        where Nev = '$nev' and Kapacitas = $kapacitas;";

        $result = mysqli_query($link, $query);

        $query = "select * from vonat
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
        where VonatID = $id";

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

        $query = "select * from vonat
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
        $query = "select * from vonat where ID = '".$a."'";
        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            echo''.$row['ID'].' | '.$row['Nev'].' | '.$row['Kapacitas'].'';
        }
    }

    if(isset($_POST['ID_v']) && isset($_POST['Nev_v']) && isset($_POST['Kapacitas_v'])){
        $rowexists = 'isrowexists';
        $tableexists = 'istableexists';
        $idcheck = 'idcheck';

        $action = mysqli_real_escape_string($link, $_POST['submit']);
        $id = mysqli_real_escape_string($link, $_POST['ID_v']);
        $nev = mysqli_real_escape_string($link, $_POST['Nev_v']);
        $kapacitas = mysqli_real_escape_string($link, $_POST['Kapacitas_v']);

        if($nev == "" || $kapacitas == ""){
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
                $rowexists($id=0, $nev, $kapacitas);
                $query = "insert into vonat(Nev, Kapacitas) values ('".$nev."','".$kapacitas."');";

                if($id != ""){
                    $rowexists($id, $nev, $kapacitas);
                    $query = "insert into vonat(ID, Nev, Kapacitas) values ('".$id."','".$nev."','".$kapacitas."');";
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

                $query = "update vonat
                set Nev = '$nev', Kapacitas = '$kapacitas'
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

                $query = "delete from vonat where ID = '".$id."'";
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