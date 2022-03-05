<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['file'])){
        include '../conf.php';

        // built a socket to db
        function dbSocket($q){
            global $db;
            $st = $db->prepare($q);
            $st->execute();
        };
        $file = $_GET['file'] == "1" ? 'notif':'messages';
        dbSocket('UPDATE '.$file.' SET `state`=0 WHERE state = 1 AND user_id='.$_SESSION['id']) ;
    }
    exit();
}
?>