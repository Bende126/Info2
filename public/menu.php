<div id="sidebar">
    <?php 
        if(isset($_SESSION['user']) && $_SESSION['belepve'] == true){
            $files = scandir('./imgs');
            unset($files[0]); // .
            unset($files[1]); // ..
            $name = $_SESSION['style'].'.png';
            $key = array_search($name, $files);
            unset($files[$key]);
            $izero = array_values($files); //az a két kép, ami nincs használatban

            ?>
            <img src="./imgs/<?php echo $_SESSION['style']; ?>.png?v=2" class="menulogo" onclick="showmenu()">
            <div id="s_menu" class="s_menu_content">
            <a href="./scripts/php/change_style.php?a=<?php echo $izero[0]; ?>"><img src="./imgs/<?php echo $izero[0]; ?>?v=3"></a>
            <a href="./scripts/php/change_style.php?a=<?php echo $izero[1]; ?>"><img src="./imgs/<?php echo $izero[1]; ?>?v=4"></a>
            </div>
            <?php
        }
        else {
            ?>
            <img src="./imgs/default.png" class="menulogo" onclick="showmenu()">
            <?php
        }
    ?>
    
    <a href="./index.php" class="menu">KEZDŐLAP</a>
    <a href="./map.php" class="menu">TÉRKÉP</a>
    <?php 
        if(!isset($_SESSION['user']) && !isset($_SESSION['belepve'])){
            echo "<a href='login_screen.php' class='menu'>BEJELENTKEZÉS</a>";
        }
    
        if(isset($_SESSION['user']) && $_SESSION['belepve'] == true){
            include './menu_user.html';
        }

        if(isset($_SESSION['user']) && $_SESSION['belepve'] == true && $_SESSION['isadmin'] == true){
            include './menu_admin.html';
        }
    ?>
    <script>
        function showmenu() {
            document.getElementById("s_menu").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.menulogo')) {
                var dropdowns = document.getElementsByClassName("s_menu_content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</div>