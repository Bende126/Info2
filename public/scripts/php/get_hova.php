<?php 
    include './db.php';
    $link = getDb();

    if (!isset($_GET['q'])){
        return;
    }

    $q = mysqli_real_escape_string($link,$_GET['q']);

    //egyáltalán van-e útvonal az állomáshoz és ha van, akkor listázzuk, hogy hova tud menni az illető
    $query = "select allomas1.ID as 'ID1', allomas1.Nev as 'Nev1', allomas2.ID as 'ID2', allomas2.Nev as 'Nev2', alo.ID as 'ID3', alo.Nev as 'Nev3'
    from utvonal
    inner join allomas allomas1 on utvonal.PointA = allomas1.ID
    inner join allomas allomas2 on utvonal.PointB = allomas2.ID
    inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
    inner join allomas alo on alu.AllomasID = alo.ID
    where allomas1.ID = '".$q."'
    or allomas2.ID = '".$q."'
    or alo.ID = '".$q."';";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0){
        $alo_list = array();
        while($row = mysqli_fetch_assoc($result)){
            if($q == $row["ID1"]){
                if(!array_key_exists($row['ID2'], $alo_list)){
                    $alo_list[$row['ID2']] = $row['Nev2'];
                }
                if(!array_key_exists($row['ID3'], $alo_list)){
                    $alo_list[$row['ID3']] = $row['Nev3'];
                }
            }
            elseif($q == $row["ID2"]){
                if(!array_key_exists($row['ID1'], $alo_list)){
                    $alo_list[$row['ID1']] = $row['Nev1'];
                }
                if(!array_key_exists($row['ID3'], $alo_list)){
                    $alo_list[$row['ID3']] = $row['Nev3'];
                }
            }
            elseif($q == $row["ID3"]){
                if(!array_key_exists($row['ID1'], $alo_list)){
                    $alo_list[$row['ID1']] = $row['Nev1'];
                }
                if(!array_key_exists($row['ID2'], $alo_list)){
                    $alo_list[$row['ID2']] = $row['Nev2'];
                }
            }
        }
        echo '<option value="0" selected disabled>Válassz végállomást</option>';
        foreach($alo_list as $x => $x_value){
            $nev = trim($x_value);
            echo'<option value="'.$x.'">'.$x.' '.$x_value.'</option>';
        }
    }
    else{
        echo '<option value="0" disabled="disabled" selected="selected">Nem található útvonal</option>';
    }
?>