<?php 
session_start();
if(isset($_SESSION['id'])){
    if(isset($_GET['me'])){
        include '../conf.php';

        // built a socket to db
        function dbSocket($q){
            global $db;
            $st = $db->prepare($q);
            $st->execute();
            return json_encode($st->fetchAll());
        };
        $check = explode('XS', $_GET['select']);
        if($check[0]=='0'){
            $select = '';
        }else if($check[0]=='1'){
            $select = 'AND  date < "'.join(' ', explode('%20',$check[1])).'"';
        }else {
            $select = 'AND  state = 1';
        }
        //get notis by selector
        if ($_GET['me']=='yes'){
            // my notis 
            echo dbSocket('SELECT frm,fq,date FROM notif WHERE user_id = '.$_SESSION['id'].' '.$select.' ORDER BY date DESC LIMIT 20 ');
        }else {
            //my fr request what i sent
            echo dbSocket('SELECT user_id FROM notif WHERE frm = '.$_SESSION['id'].' AND fr = 1 ORDER BY date DESC LIMIT 20 ');
        }

    }
    exit();
}
?>