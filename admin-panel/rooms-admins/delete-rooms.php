<?php
    require "../../config/config.php";
   if(isset($_GET['id'])){
    $id = $_GET['id'];
    $image = $conn->prepare("SELECT image FROM rooms WHERE id = '$id'");
    $image->execute();
    $fetch = $image->fetch(PDO::FETCH_OBJ);
    unlink("../../images/" . $fetch->image);
    $delete = $conn->prepare("DELETE FROM rooms WHERE id = '$id'");
    $delete->execute();
    header("location: show-rooms.php");
    exit;
   }
   else{
    header("location: show-rooms.php");    
    exit;
   }
?>