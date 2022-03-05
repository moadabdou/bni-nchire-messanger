<?php
$dsn = 'mysql:host=localhost;dbname=chat';
$user = 'root';
$psw = '';
$op = array (
  PDO::MYSQL_ATTR_INIT_COMMAND=> 'SET NAMES utf8'
);
try {
    $db = new PDO ($dsn, $user, $psw, $op);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}catch(PDOException $e){
  // echo $e->getMessage();
  header('Location:serverdown.html');
  echo '): |-:-:-|  {SORRY} =>  some error is happened or you cant brows this bage or this page was not found ';
  
}
?>