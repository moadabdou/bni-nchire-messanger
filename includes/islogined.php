<?php 
include '../conf.php';
if (isset($_GET['name'])){
    $st = $db->prepare('SELECT name FROM users WHERE name = ?');
    $st->execute(array($_GET['name']));
    if ($st->rowCount()>0){
        echo 'yes';
    }else{
        echo 'no';
    }
    exit();
}else {
    header('Location:../index.php');
    exit();
}
?>