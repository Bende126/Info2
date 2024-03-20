<?php
    include './header.php'; 
    if (isset($_GET['error'])){
        echo "<div class='error'> <h2>". $_GET['error']."</h2></div>";
    }
?>

<div id="forms">
    <form action="./scripts/php/login.php" method="post" class="login">
        <input type="text" name="username" id="username" required placeholder="Felhasználónév"><br>
        <input type="password" name="password" id="password" required placeholder="Jelszó"><br>
        <input type="submit" value="Bejelentkezés">
    </form>

   <div class="vonal"></div>

    <form action="./scripts/php/register.php" method="post" class="register">
        <input type="text" name="username" id="username" required placeholder="Felhasználónév"><br>
        <input type="password" name="password" id="password" required placeholder="Jelszó"><br>
        <input type="text" name="utas" id="utas" required placeholder="Név"><br>
        <input type="tel" name="telefon" id="telefon" placeholder="Telefon: +36-12-345-6789" pattern="+36-[0-9]{2}-[0-9]{3}-[0-9]{4}"><br>
        <input type="email" name="email" id="email" placeholder="Email-cím: abc@example.com"><br>
        <input type="submit" value="Regisztráció">
    </form>
    </div>
<?php 
    include './footer.html';
?>