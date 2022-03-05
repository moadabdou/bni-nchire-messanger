<?php
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['id'])){
        include '../conf.php';

        // built a socket to db
        function dbSocket($q){
            global $db;
            $st = $db->prepare($q);
            $st->execute();
            return $st;
        }
        //get others infos
        $con= 'add';
        $st = dbSocket('SELECT name,user_id,img FROM users WHERE user_id = '.$_GET['id']);
        if($st->rowCount()>0){
            if(dbSocket('SELECT date FROM rels WHERE (p1='.$_GET['id'].' AND  p2='.$_SESSION['id'].') OR (p1='.$_SESSION['id'].' AND  p2='.$_GET['id'].')')->rowCount()>0){
                $con = 'remove';
            }else if(dbSocket('SELECT fq FROM notif WHERE user_id='.$_SESSION['id'].' AND  frm='.$_GET['id'])->rowCount()>0){
                $con = 'double';
            }else if(dbSocket('SELECT fq FROM notif WHERE user_id='.$_GET['id'].' AND  frm='.$_SESSION['id'])->rowCount()>0){
                $con = 'cancel';
            };
    
            $row = $st->fetch();
            $row['controle']=$con;
            echo json_encode($row);
        }

    }
    exit();
}
?>