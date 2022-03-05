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
        $sel = join(' ', explode('%20',$_GET['sel']));
        $data = dbSocket('SELECT from_user, date , message FROM messages  WHERE ((from_user=:md AND to_user=:hd) OR (from_user=:hd AND to_user=:md)) AND date < "'.$sel.'" ORDER BY date DESC LIMIT 20', array('md'=>$_SESSION['id'], 'hd'=>$_GET['id']));
        echo  json_encode($data->fetchAll());
        exit();
    }
}
?>