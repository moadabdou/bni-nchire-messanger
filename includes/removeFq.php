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
        
        $st = dbSocket('SELECT user_id FROM users WHERE user_id =?',array($id));
        if($st->rowCount()>0){
            if($_GET['op']=='my'){
                dbSocket('DELETE FROM `notif` WHERE user_id=? AND frm=? AND fr=1',array($id, $_SESSION['id']));
            }else {
                dbSocket('DELETE FROM `notif` WHERE user_id=? AND frm=? AND fr=1',array( $_SESSION['id'],$id));
            }
            echo '1';
        }

    }
    exit();
}
