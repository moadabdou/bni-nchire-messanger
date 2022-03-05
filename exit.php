<?php
session_start();
include 'init.php';
if (isset($_SESSION['id'])){
    $con= $db->prepare('UPDATE login_details SET `state`=? WHERE user_id =?');
                        $con->execute(array(
                            0,
                            $_SESSION['id']
                        ));
    session_unset();
    if (ini_get('session.use_cookies')){
        $pr = session_get_cookie_params();
        setcookie(session_name(), time()-4000,array(
            $pr['path'], $pr['domain'], $pr['secure'], $pr['httponly']
        ));
    session_destroy();
    re();
    }
}else{
    re();
}

?>