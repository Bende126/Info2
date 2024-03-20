<?php 
    include './scripts/php/check_login.php';
    include './header.php';
    include './scripts/php/db.php';
    if (isset($_GET['error'])){
        echo "<div class='error'> <h2>". $_GET['error']."</h2></div>";
    }
?>

<div id="ticket">
    <div class="forms">
    <div class="dropdown">
    <form action="./scripts/php/buy.php" method="post" class="buy">
        <label for="honnan">Honnan: </label>
        <select name="honnan" id="honnan" required onchange="change_allomas(this.value)">
            <option value="0" selected disabled>Válassz kezdőállomást</option>
            <?php
                $link = getDb();
                $query = "select ID, Nev from allomas;";
                $result = mysqli_query($link, $query);
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="'.$row['ID'].'">'.$row['ID'].'  '.$row['Nev'].'</option>';
                    }
                }
            ?>
        </select>
        <label for="hova">Hova:</label>
        <select name="hova" id="hova" required onchange="drawutvonal(this.value)"></select>
        <input type="submit" value="Jegyvásárlás">
        </form>
        </div>
        <div class="user">

                <?php
                    $query = "select * from felhasznalo where ID = '".$_SESSION['user']."'";
                    $result = mysqli_query($link, $query);
                    $user = mysqli_fetch_assoc($result);
                ?>
                <form action="">
                    <label for="nev">Név:</label>
                    <?php 
                        echo '<input type="text" name="nev" id="nev" disabled placeholder="'.$user['Nev'].'">'; 
                    ?>
                    <br>
                    <label for="email">E-mail:</label>
                    <?php 
                        echo '<input type="text" name="email" id="email" disabled placeholder="'.$user['Email'].'">';
                    ?>
                    <br>
                    <label for="telefon">Telefon:</label>
                    <?php
                        echo '<input type="text" name="telefon" id="telefon" disabled placeholder="'.$user['Telefon'].'">';
                    ?>
                </form>
            <table>
                <tr>
                    <th>Honnan</th>
                    <th>Hova</th>
                    <th>Érvényes</th>
                </tr>
                <?php
                    $query = "select allomas1.Nev as 'Honnan', allomas2.Nev as 'Hova', jg.Ervenyes from felhasznalo
                    inner join jegy_felhasznalo jhf on jhf.FelhasznaloID = felhasznalo.ID
                    inner join jegy jg on jg.ID = jhf.JegyID
                    inner join allomas allomas1 on allomas1.ID = jg.Honnan
                    inner join allomas allomas2 on allomas2.ID = jg.Hova
                    where felhasznalo.ID = '".$_SESSION['user']."'and jg.Ervenyes >= curdate();";

                    $ervenyes = mysqli_query($link, $query);

                    $query = "select allomas1.Nev as 'Honnan', allomas2.Nev as 'Hova', jg.Ervenyes from felhasznalo
                    inner join jegy_felhasznalo jhf on jhf.FelhasznaloID = felhasznalo.ID
                    inner join jegy jg on jg.ID = jhf.JegyID
                    inner join allomas allomas1 on allomas1.ID = jg.Honnan
                    inner join allomas allomas2 on allomas2.ID = jg.Hova
                    where felhasznalo.ID = '".$_SESSION['user']."'and jg.Ervenyes < curdate();";

                    $ervenytelen = mysqli_query($link, $query);

                    if (mysqli_num_rows($ervenyes) > 0 || mysqli_num_rows($ervenytelen) > 0){
                        while($row = mysqli_fetch_assoc($ervenyes)){
                            echo '<tr>
                            <td>'.$row['Honnan'].'</td>
                            <td>'.$row['Hova'].'</td>
                            <td class="ervenyes">'.$row['Ervenyes'].'</td>
                            </tr>';
                        }

                        while($row = mysqli_fetch_assoc($ervenytelen)){
                            echo '<tr>
                            <td>'.$row['Honnan'].'</td>
                            <td>'.$row['Hova'].'</td>
                            <td class="ervenytelen">'.$row['Ervenyes'].'</td>
                            </tr>';
                        }
                    }
                ?>
            </table>
        </div>
        </div>
        <br>
        <div id="map-container">
        <div id="map"></div>
        <br>
</div>
</div>

<script>
    function change_allomas(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("hova").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "./scripts/php/get_hova.php?q=" + id, true);
        xmlhttp.send();
    }

    function drawutvonal(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("map").innerHTML = this.responseText;
            }
        };
        var e = document.getElementById("honnan");
        var pointid = e.value;
        xmlhttp.open("GET", "./scripts/php/map_for_ticket.php?a=" + id + "&b=" + pointid, true);
        xmlhttp.send();
    }

</script>

<?php
    closeDb($link);
    include './footer.html';
?>