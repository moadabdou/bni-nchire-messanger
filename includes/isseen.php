<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['id'])){
        include '../conf.php';
        $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
        $date = join(' ', explode('%20',$_GET['date']));
        // built a socket to db
        function dbSocket($q, $p){
            global $db;
            $st = $db->prepare($q);
            $st->execute($p);
            return $st;
        }
        
        $data = dbSocket('SELECT date FROM `messages` WHERE from_user=?  AND to_user=? AND   `state`=0 AND date=?  ', array( $_SESSION['id'], $id, $date));
        if($data->rowCount()>0){
            echo '1';
        }
    }
    exit();
}


?>