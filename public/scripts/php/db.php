<?php
function getDb() {
    $link = mysqli_connect('localhost','labor','asdf1234','mav');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
      }
    return $link;   
}

function closeDb($link) {
    mysqli_close($link);
}
?>