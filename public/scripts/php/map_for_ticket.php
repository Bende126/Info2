<?php 
    include './db.php';
    $link = getDb();

    if (!isset($_GET['a']) || !isset($_GET['b'])){
        return;
    }
    $a = mysqli_real_escape_string($link, $_GET['a']);
    $b = mysqli_real_escape_string($link, $_GET['b']);

    $query = "select allomas1.ID as 'ID1', allomas1.Nev as 'Nev1', allomas2.ID as 'ID2', allomas2.Nev as 'Nev2', alo.ID as 'ID3', alo.Nev as 'Nev3', utvonal.ID, utvonal.Ut_nev
    from utvonal
    inner join allomas allomas1 on utvonal.PointA = allomas1.ID
    inner join allomas allomas2 on utvonal.PointB = allomas2.ID
    inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
    inner join allomas alo on alu.AllomasID = alo.ID
    where (allomas1.ID = '".$a."' or allomas2.ID = '".$a."' or alo.ID = '".$a."') 
    and (allomas1.ID = '".$b."' or allomas2.ID = '".$b."' or alo.ID = '".$b."');";

    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        $ut_list = array();
        while($row = mysqli_fetch_assoc($result)){
            if(!array_key_exists($row['ID'], $ut_list)){
                $ut_list[$row['ID']] = $row['Ut_nev'];
            }
        }
        foreach($ut_list as $x => $x_value){
            $command = escapeshellcmd("../map/drawmap.py --id=".$x);
            $output = shell_exec($command);
            echo $output;
        }
    }
    else {
        return;
    }

    closeDb($link);
?>