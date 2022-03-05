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
    $check = explode('XS', $_GET['select']);
    if($check[0]=='0'){
        $select = '';
    }else if($check[0]=='1'){
        $select = 'AND  date < "'.join(' ', explode('%20',$check[1])).'"';
    }else {
        $select = 'AND  date > "'.join(' ', explode('%20',$check[1])).'"';
    }
    
    $rels = dbSocket('SELECT * FROM rels WHERE (p1='.$_SESSION['id'].' OR p2='.$_SESSION['id'].') '.$select.' ORDER BY date DESC LIMIT 20');
    $rels = $rels->fetchAll();
    $data = [];
    foreach($rels as $key){
        $rel = [];
        if($key['p1'] == $_SESSION['id']){
            $rel[] = $key['p2'];
        }else {
            $rel[] = $key['p1'];
        }
        $rel['date'] =$key['date'];
        $data[] = $rel;
    }
    echo json_encode($data);
    exit();

}


?>