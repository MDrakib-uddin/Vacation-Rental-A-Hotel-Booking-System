<?php
try{
if (!defined('HOST')) define('HOST', 'localhost');
if (!defined('USER')) define('USER', 'root');
if (!defined('PASS')) define('PASS', '');
if (!defined('DBNAME')) define('DBNAME', 'hotel-booking');
if (!defined('APPURL')) define('APPURL', 'http://localhost/hotel-booking');
if (!defined('ADMINURL')) define('ADMINURL', 'http://localhost/hotel-booking/admin-panel');

$conn = new PDO("mysql:host=".HOST.";dbname=".DBNAME, USER, PASS);
}catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}
?>
