<?php 
session_start();
if(isset($_SESSION['id'])){
    include '../conf.php';
    if(isset($_GET['id'])){
                // built a socket to db
        function dbSocket($q, $p){
            global $db;
            $st = $db->prepare($q);
            $st->execute($p);
            return $st;
        };
        $data = dbSocket('SELECT from_user, date , message FROM messages  WHERE (from_user=:md AND to_user=:hd) OR (from_user=:hd AND to_user=:md)  ORDER BY date DESC LIMIT 20', array('md'=>$_SESSION['id'], 'hd'=>$_GET['id']));
        dbSocket('UPDATE `messages` SET state=0 WHERE from_user=? AND `state`=1 ', array( $_GET['id']));
        echo  json_encode($data->fetchAll());
        exit();
    }
}


?>