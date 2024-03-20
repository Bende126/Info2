<?php
    include './header.php';
    if (isset($_GET['error'])){
        echo "<div class='error'> <h2>". $_GET['error']."</h2></div>";
    }
?>
    <h1>Jegy és Vasútkezelő Weboldal</h1> <br>
    <p> Üdvözöljük a tisztelt látogatót! Kérem használja a felső menüsort a navigálásra. <br>
    Ön jelenleg a Főoldalon tartózkodik, ha szeretne oldalt váltani, akkor kattintson a megfelelő menü gombra.<br>
    A Térkép oldalon meg tudja tekinteni a jelenlegi vasúthálózatot.<br>
    Bejelentkezés után jegyet is képes váltani a weboldalon.<br>
    </p>
<?php 
    include './footer.html';
?>