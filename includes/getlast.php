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
    };
    $rels = dbSocket('SELECT from_user FROM messages WHERE to_user='.$_SESSION['id'].' AND from_user IN ('.$_GET['frs'].') AND state =1');
    echo json_encode($rels->fetchAll());
    exit();

}


?>