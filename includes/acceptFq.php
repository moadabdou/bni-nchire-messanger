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
            dbSocket('INSERT INTO `rels`(p1, p2, `date`) VALUES (?,?,?)', array($id, $_SESSION['id'],date('Y-m-d H:i:s')));
            echo '1';
        }

    }
}

?>