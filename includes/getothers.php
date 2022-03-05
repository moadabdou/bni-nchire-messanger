<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['last'])){
        include '../conf.php';
        
        $check = explode( 'XS',$_GET['last']);
        $selector = $check[0] =='1'? 'IN('.$check[1].')': '> '.$check[1]; 
        $l = $check[0] =='1'? '': ' LIMIT 20 '; 
        // built a socket to db
        function dbSocket($q){
            global $db;
            $st = $db->prepare($q);
            $st->execute();
            return json_encode($st->fetchAll());
        }
        //get others infos
        echo dbSocket('SELECT name,user_id,img FROM users WHERE user_id '.$selector.' AND user_id!='.$_SESSION['id'].$l);
        
    }
    exit();
}
?>