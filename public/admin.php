<?php 
    include './scripts/php/check_login.php';
    include './header.php';
    include './scripts/php/db.php';

    if (isset($_GET['error'])){
        echo "<div class='error'> <h2>". $_GET['error']."</h2></div>";
    }

    $link = getDb();
?>
<div id="adminpanel">
<a href="./scripts/php/init_db.php">Reset database</a><br><br>
<form action="./scripts/php/admin/allomas.php" method="post">
    <h2>Állomás</h2> <br>
<select name="" id="" onchange="allomas(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from allomas;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|Nev: '.$row['Nev'].
                    '|Coord_x: '.$row['Coord_x'].
                    '|Coord_y: '.$row['Coord_y'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_a" id="ID_a">
    <input type="text" name="Nev_a" id="Nev_a">
    <input type="text" name="Coord_x_a" id="Coord_x_a">
    <input type="text" name="Coord_y_a" id="Coord_y_a">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/allomas_utvonal.php" method="post">
<h2>Állomás - Útvonal</h2> <br>
<select name="" id="" onchange="allomas_utvonal(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from allomas_utvonal;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|AllomasID: '.$row['AllomasID'].
                    '|UtvonalID: '.$row['UtvonalID'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_au" id="ID_au">
    <input type="text" name="AllomasID_au" id="AllomasID_au">
    <input type="text" name="UtvonalID_au" id="UtvonalID_au">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/felhasznalo.php" method="post">
<h2>Felhasználó</h2> <br>
<select name="" id="" onchange="felhasznalo(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from felhasznalo;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|Nev: '.$row['Nev'].
                    '|Telefon: '.$row['Telefon'].
                    '|Email: '.$row['Email'].
                    '|UserName: '.$row['UserName'].
                    '|Passwd: '.$row['Passwd'].
                    '|IsAdmin: '.$row['IsAdmin'].
                    '|Style: '.$row['Style'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_f" id="ID_f">
    <input type="text" name="Nev_f" id="Nev_f">
    <input type="tel" name="Telefon_f" id="Telefon_f">
    <input type="email" name="Email_f" id="Email_f">
    <input type="text" name="UserName_f" id="UserName_f">
    <input type="text" name="Passwd_f" id="Passwd_f">
    <input type="text" name="IsAdmin_f" id="IsAdmin_f">
    <input type="text" name="Style_f" id="Style_f">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/jegy.php" method="post">
<h2>Jegy</h2> <br>
<select name="" id="" onchange="jegy(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from jegy;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|Honnan: '.$row['Honnan'].
                    '|Hova: '.$row['Hova'].
                    '|Utas: '.$row['Utas'].
                    '|Ervenyes: '.$row['Ervenyes'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_j" id="ID_j">
    <input type="text" name="Honnan_j" id="Honnan_j">
    <input type="text" name="Hova_j" id="Hova_j">
    <input type="text" name="Utas_j" id="Utas_j">
    <input type="date" name="Ervenyes_j" id="Ervenyes_j">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/jegy_felhasznalo.php" method="post">
<h2>Jegy - Felhasználó</h2> <br>
<select name="" id="" onchange="jegy_felhasznalo(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from jegy_felhasznalo;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|JegyID: '.$row['JegyID'].
                    '|FelhasznaloID: '.$row['FelhasznaloID'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_jf" id="ID_jf">
    <input type="text" name="JegyID_jf" id="JegyID_jf">
    <input type="text" name="FelhasznaloID_jf" id="FelhasznaloID_jf">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/utvonal.php" method="post">
<h2>Útvonal</h2> <br>
<select name="" id="" onchange="utvonal(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from utvonal";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|Ut_nev: '.$row['Ut_nev'].
                    '|PointA: '.$row['PointA'].
                    '|PointB: '.$row['PointB'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_u" id="ID_u">
    <input type="text" name="Ut_nev_u" id="Ut_nev_u">
    <input type="text" name="PointA_u" id="PointA_u">
    <input type="text" name="PointB_u" id="PointB_u">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/vonat.php" method="post">
<h2>Vonat</h2> <br>
<select name="" id="" onchange="vonat(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from vonat;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|Nev: '.$row['Nev'].
                    '|Kapacitas: '.$row['Kapacitas'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_v" id="ID_v">
    <input type="text" name="Nev_v" id="Nev_v">
    <input type="text" name="Kapacitas_v" id="Kapacitas_v">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
<form action="./scripts/php/admin/vonat_utvonal.php" method="post">
<h2>Vonat - Útvonal</h2> <br>
    <select name="" id="" onchange="vonat_utvonal(this.value)">
        <option value="0" selected disabled>Válassz egy sort</option>
        <?php
            $query = "select * from vonat_utvonal;";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="'.$row['ID'].'">ID: '.$row['ID'].
                    '|VonatID: '.$row['VonatID'].
                    '|UtvonalID: '.$row['UtvonalID'].
                    '</option>';
                }
            } 
        ?>
    </select>
    <br>
    <input type="text" name="ID_vu" id="ID_vu">
    <input type="text" name="VonatID_vu" id="VonatID_vu">
    <input type="text" name="UtvonalID_vu" id="UtvonalID_vu">
    <br>
    <input type="submit" value="Create" name="submit">
    <input type="submit" value="Edit" name="submit">
    <input type="submit" value="Delete" name="submit">
</form>
</div>

<script>
    function vonat_utvonal(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID UtvonalID VonatID
                document.getElementById("ID_vu").value = vals[0];
                document.getElementById("UtvonalID_vu").value = vals[1];
                document.getElementById("VonatID_vu").value = vals[2];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/vonat_utvonal.php?a=" + id, true);
        xmlhttp.send();
    }

    function vonat(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID Nev Kapacitas
                document.getElementById("ID_v").value = vals[0];
                document.getElementById("Nev_v").value = vals[1];
                document.getElementById("Kapacitas_v").value = vals[2];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/vonat.php?a=" + id, true);
        xmlhttp.send();
    }

    function utvonal(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID Utvonal_nev PointA PointB
                document.getElementById("ID_u").value = vals[0];
                document.getElementById("Ut_nev_u").value = vals[1];
                document.getElementById("PointA_u").value = vals[2];
                document.getElementById("PointB_u").value = vals[3];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/utvonal.php?a=" + id, true);
        xmlhttp.send();
    }

    function jegy(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID Honnan Hova Utas Ervenyes
                document.getElementById("ID_j").value = vals[0];
                document.getElementById("Honnan_j").value = vals[1];
                document.getElementById("Hova_j").value = vals[2];
                document.getElementById("Utas_j").value = vals[3];
                document.getElementById("Ervenyes_j").value = vals[4];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/jegy.php?a=" + id, true);
        xmlhttp.send();
    }

    function jegy_felhasznalo(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID JegyID FelhasznaloID
                document.getElementById("ID_jf").value = vals[0];
                document.getElementById("JegyID_jf").value = vals[1];
                document.getElementById("FelhasznaloID_jf").value = vals[2];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/jegy_felhasznalo.php?a=" + id, true);
        xmlhttp.send();
    }

    function allomas_utvonal(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID AllomasID UtvonalID
                document.getElementById("ID_au").value = vals[0];
                document.getElementById("AllomasID_au").value = vals[1];
                document.getElementById("UtvonalID_au").value = vals[2];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/allomas_utvonal.php?a=" + id, true);
        xmlhttp.send();
    }

    function allomas(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID Nev Coord_x Coord_y  
                document.getElementById("ID_a").value = vals[0];
                document.getElementById("Nev_a").value = vals[1];
                document.getElementById("Coord_x_a").value = vals[2];
                document.getElementById("Coord_y_a").value = vals[3];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/allomas.php?a=" + id, true);
        xmlhttp.send();
    }

    function felhasznalo(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const vals = this.responseText.split(' | '); // ID Nev Telefon Email UserName Passwd IsAdmin
                document.getElementById("ID_f").value = vals[0];
                document.getElementById("Nev_f").value = vals[1];
                document.getElementById("Telefon_f").value = vals[2];
                document.getElementById("Email_f").value = vals[3];
                document.getElementById("UserName_f").value = vals[4];
                document.getElementById("Passwd_f").value = "";
                document.getElementById("IsAdmin_f").value = vals[6];
                document.getElementById("Style_f").value = vals[7];
            }
        };
        xmlhttp.open("GET", "./scripts/php/admin/felhasznalo.php?a=" + id, true);
        xmlhttp.send();
    }
</script>

<?php
    closeDb($link);
    include './footer.html';
?>
