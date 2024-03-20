<?php
    include './check_login.php';
    include './db.php';
    $link = getDb();

    if(!isset($_POST['honnan']) || !isset($_POST['hova'])){
        $error = 'POST error /:';
        header('Location: ../../index.php?error='.$error);
    }

    if($_POST['honnan'] == '0' || $_POST['hova'] == '0'){
        $error = "Form error ;/";
        header('Location: ../../ticket.php?error='.$error);
    }

    $honnan = mysqli_real_escape_string($link, $_POST['honnan']);
    $hova = mysqli_real_escape_string($link, $_POST['hova']);
    $query = "select allomas1.ID as 'ID1', allomas1.Nev as 'Nev1', allomas2.ID as 'ID2', allomas2.Nev as 'Nev2', alo.ID as 'ID3', alo.Nev as 'Nev3', utvonal.ID, utvonal.Ut_nev
    from utvonal
    inner join allomas allomas1 on utvonal.PointA = allomas1.ID
    inner join allomas allomas2 on utvonal.PointB = allomas2.ID
    inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
    inner join allomas alo on alu.AllomasID = alo.ID
    where (allomas1.ID = '".$honnan."' or allomas2.ID = '".$honnan."' or alo.ID = '".$honnan."') 
    and (allomas1.ID = '".$hova."' or allomas2.ID = '".$hova."' or alo.ID = '".$hova."');";

    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        $ut_list = array();
        $vonat_list = array();

        while($row = mysqli_fetch_assoc($result)){
            if(!array_key_exists($row['ID'], $ut_list)){
                $ut_list[$row['ID']] = $row['Ut_nev'];
            }
        }

        foreach($ut_list as $x => $x_value){
            $query = "select vn.* from vonat_utvonal
            inner join vonat vn on vn.ID = vonat_utvonal.VonatID
            where vonat_utvonal.UtvonalID = '".$x."';";
            $vonat = mysqli_query($link, $query);
            if (mysqli_num_rows($vonat) > 0){
                while($row = mysqli_fetch_assoc($vonat)){
                    if(!array_key_exists($row['ID'], $vonat_list)){
                        $vonat_list[$row['ID']] = $row['Nev'];
                    }
                }
            }
        }
        /*ut_list: összes lehetséges útvonal honnan és hova között*/
        /*vonat_list: összes lehetséges vonat az ut_list-ben található utakon*/
        $query = "insert into jegy(Honnan, Hova, Utas, Ervenyes) values('".$honnan."', '".$hova."', '".$_SESSION['user']."', date_add(curdate(), interval 1 week));"; 
        if(count($vonat_list) > 0){
            $query = "insert into jegy(Honnan, Hova, Utas, Ervenyes) values('".$honnan."', '".$hova."', '".$_SESSION['user']."', date_add(curdate(), interval 1 day));";
        }
        
        $result = mysqli_query($link, $query);

        if(!$result){
            $error = "Adatbázis insert hiba:".mysqli_errno($link).":/";
            header('Location: ../../ticket.php?error =' .$error);
        }

        $query = "select ID from jegy
        order by ID desc;"; /*valamiért nem működik az alábbiakkal:
                            "select max(ID) as 'ID' from jegy;"
                            "select last_insert_id as 'ID' from jegy;"
                            🤷‍♂️
                            */
        $result = mysqli_query($link, $query);
        $lst = mysqli_fetch_assoc($result);
        $query = "insert into jegy_felhasznalo(JegyID, FelhasznaloID) values('".$lst['ID']."','".$_SESSION['user']."');";
        $result = mysqli_query($link,$query);

        if(!$result){
            $error = "Adatbázis insert hiba:".mysqli_errno($link).":/";
            header('Location: ../../ticket.php?error =' .$error);
        }
    }

    closeDb($link);
    header('Location: ../../ticket.php');
?>