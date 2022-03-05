<?php 
session_start();
if(isset($_SESSION['id'])){
    include '../conf.php';

    // built a socket to db
    function dbSocket($q){
        global $db;
        $st = $db->prepare($q);
        $st->execute();
        return $st;
    };
    $rels = dbSocket('SELECT p1,p2 FROM rels WHERE (p1='.$_SESSION['id'].' OR p2='.$_SESSION['id'].') ');
    $rels = $rels->fetchAll();
    $data = '';
    foreach($rels as $key){
        if($key['p1'] == $_SESSION['id']){
            $data.=strval($key['p2']);
        }else {
            $data.=strval($key['p1']);
        }
    }
    $rels = dbSocket('SELECT user_id FROM login_details WHERE user_id IN('.$data.')  AND state = 1');
    $rels = $rels->fetchAll();
    $data ='';
    foreach($rels as $key){
            $data.=strval($key[0]);
    }
    echo json_encode($data);
    exit();

}


?>