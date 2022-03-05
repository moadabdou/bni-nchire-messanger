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
            dbSocket('INSERT INTO `notif`(`user_id`, `frm`, `fq`, `date`, `state`) VALUES (?,?,?,?,?)', array($id, $_SESSION['id'], 'sent a friend request to you',date('Y-m-d H:i:s'), 1));
            echo '1';
        }

    }
}

?>