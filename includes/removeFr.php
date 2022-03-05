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
            dbSocket('DELETE FROM rels WHERE (p1=:md AND  p2=:hd) OR (p1=:hd AND  p2=:md)',array('md'=>$_SESSION['id'],'hd'=>$_GET['id']));
            echo '1';
        }
    }
    exit();
}

?>