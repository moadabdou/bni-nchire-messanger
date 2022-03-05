<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['id'])){
        include '../conf.php';
        $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
        // built a socket to db
        function dbSocket($q, $p){
            global $db;
            $st = $db->prepare($q);
            $st->execute($p);
            return $st;
        }
        
        $st = dbSocket('SELECT p1,p2 FROM rels WHERE (p1=:md AND  p2=:hd) OR (p1=:hd AND  p2=:md)',array('md'=>$_SESSION['id'],'hd'=>$id));
        if($st->rowCount() > 0){
            $data = dbSocket('SELECT from_user, `date`, message FROM `messages` WHERE from_user=? AND   `state`=1  ', array( $id));
            if($data->rowCount()>0){
                dbSocket('UPDATE`messages` SET state=0 WHERE from_user=? AND   `state`=1 ', array( $id));
            }
            echo json_encode($data->fetchAll());
        }
        

    }
    exit();
}


?>