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
            return $st->fetch()[0];
        };
        $file = $_GET['file'] == "1" ? 'notif':'messages';
        echo dbSocket('SELECT COUNT(state) FROM '.$file.' WHERE state = 1 AND user_id='.$_SESSION['id']) ;
    }
    exit();
}
?>