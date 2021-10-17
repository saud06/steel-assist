<?php
    $host = 'localhost';

    $username = 'root';
    $password = 'mysql';
    $database = 'rrmsteel_purchase';

    // $username = 'rrmstee1';
    // $password = 'Jksa(@#*9JIDNm893h';
    // $database = 'rrmstee1_inventory';

    $conn = mysqli_connect($host, $username, $password, $database) or die ('<center><h1>DISCONNECTED !</h1></center>');
?>