<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>C5AAEK Házifeladat</title>
    <?php
        session_start();
        if(isset($_SESSION['user']) && $_SESSION['belepve'] == true){
            echo '<link rel="stylesheet" type="text/css" href="./css/'.$_SESSION['style'].'.css?v=1">';
        }
        else {
            echo '<link rel="stylesheet" type="text/css" href="./css/default.css">';
        }
    ?>
</head>
<body>
<?php
    include './menu.php';
    
    if(isset($_SESSION['user']) && $_SESSION['belepve'] == true && $_SESSION['username'] =='666ghost'){
        ?>
        <div id="secret">
        <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Visuals by: Mágus Bece</a>
        </div>
        <?php
    }
    ?>
<div id="content">