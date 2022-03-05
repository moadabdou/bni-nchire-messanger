<?php 
session_start();
if(isset($_SESSION['id'])){
    include '../conf.php';

    // built a socket to db
    function dbSocket($q){
        global $db;
        $st = $db->prepare($q);
        $st->execute();
        return $st;
    }

    $st = dbSocket('SELECT name,user_id FROM users WHERE name="'.$_GET['word'].'"');
    echo json_encode($st->fetch());
    exit();
}
