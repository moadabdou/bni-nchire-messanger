<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['id'])){
        include '../conf.php';
        $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
        $msg = filter_var($_GET['message'], FILTER_SANITIZE_STRING);
        // built a socket to db
        function dbSocket($q, $p){
            global $db;
            $st = $db->prepare($q);
            $st->execute($p);
            return $st;
        }
        
        $st = dbSocket('SELECT p1,p2 FROM rels WHERE (p1=:md AND  p2=:hd) OR (p1=:hd AND  p2=:md)',array('md'=>$_SESSION['id'],'hd'=>$id));
        if($st->rowCount() > 0){
            $dateset = date('Y-m-d H:i:s');
            dbSocket('INSERT INTO `messages`(from_user,to_user,  `date`, `state`, message) VALUES (?,?,?,?,?)', array( $_SESSION['id'],$id,$dateset, 1, $msg));
            dbSocket('UPDATE rels SET date=:d WHERE (p1=:md AND  p2=:hd) OR (p1=:hd AND  p2=:md)',array('md'=>$_SESSION['id'],'hd'=>$id,'d'=>$dateset));
            echo $dateset;
        }else {
            echo '2';
        }
        
    }
    exit();
}

?>