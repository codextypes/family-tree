<?php
try {
    $connection = new PDO("mysql:host=localhost;dbname=family_tree", 'root', '');
    // set the PDO error mode to exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    $connection = null;
    die();
}