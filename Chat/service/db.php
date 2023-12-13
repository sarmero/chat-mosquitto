<?php

$pass = "";
$usr = "root";
$dbn = "chat";
$host = "localhost";
$port = "3306";

try {
    $db = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbn",
        $usr,
        $pass
    );
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    
    echo "Exception in DB: " . $e->getMessage();
}
?>