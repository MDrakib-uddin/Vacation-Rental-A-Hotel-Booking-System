<?php
    require "../../config/config.php";
   if(isset($_GET['id'])){
    $id = $_GET['id'];
    $image = $conn->prepare("SELECT image FROM hotels WHERE id = '$id'");
    $image->execute();
    $fetch = $image->fetch(PDO::FETCH_OBJ);
    unlink("../../images/" . $fetch->image);
    $delete = $conn->prepare("DELETE FROM hotels WHERE id = '$id'");
    $delete->execute();
    header("location: show-hotels.php");
    exit;
   }
   else{
    header("location: show-hotels.php");
    exit;
   }
?>