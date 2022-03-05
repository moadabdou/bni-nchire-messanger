<?php
session_start();
if (!isset($_SESSION['id'])){
if ($_SERVER['REQUEST_METHOD']=='POST'){
    include 'init.php';
    if (isset($_POST['login'])){
        $st = $db->prepare('SELECT * FROM users WHERE name =? AND pass = ?');
        $st -> execute(array($_POST['user'],sha1($_POST['pass'])));
        if ($st->rowCount()>0){
            $row = $st->fetch();
            $_SESSION['id']= $row['user_id'];
            $_SESSION['name']= $row['name'];
            $_SESSION['img']= $row['img'];
            $con= $db->prepare('UPDATE login_details SET `state`=? WHERE user_id =?');
                    $con->execute(array(
                        1,
                        $row['user_id']
                    ));

            //header('location:home.php');
            exit();
        }else{

        }
    }else if (isset($_POST['singup'])){
        if (empty($_POST['user']) or 
            empty($_POST['pass']) or 
            strlen($_POST['user']) > 20 or
            strlen($_POST['user']) < 6 or 
            strlen($_POST['pass']) > 20 or 
            strlen($_POST['pass']) < 6
            ){
                //re();
            }else{
                $user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
                $pass = sha1(filter_var($_POST['pass'],FILTER_SANITIZE_STRING));
                $st = $db->prepare('INSERT INTO `users`(`name`,pass) VALUES( ?,?)');

                try {    
                    $st->execute(array($user,$pass));
                    $st = $db -> prepare('SELECT user_id FROM users WHERE name=?');	echo $row['user_id'] ;
                    $st->execute(array($user));
                    if ($st->rowCount()>0){ 
                        $row = $st->fetch();
                         
                        $st = $db -> prepare('INSERT INTO login_details (user_id) VALUES(?)');
                        $st->execute(array($row['user_id']));
                    }
                }catch(PDOException $e){
                    echo $e;
                }         
		//re();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcom</title>
    <link rel="stylesheet" href="design/css/font-awesome.min.css">
    <link rel="stylesheet" href="design/css/welcom.css">
</head>
<body>
    <b> created BY MOAD ABDOU fb:BymoadmoadbBy</b>
    <div class="welcom">
        <div class="controle">
            <div class="ef">
            </div>
            <div class="left">
                login
            </div>
            <div class="right">
                register
            </div>
        </div>
        <div class="move">
            <div class="box">
                <div class="form">
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                        <input type="hidden" name="login">
                        <input type="text" placeholder="your name" name="user"/>
                        <input type="password" placeholder="your password" name="pass"/>
                        <input type="submit" id='l' value="log in">
                    </form>
                </div>
            </div>
            <div class="box">
                <div class="form">
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                        <input type="hidden" name="singup">                       
                        <i class="fa fa-circle-o-notch fa-spin  fa-fw"></i>
                        <span>this name is already exest</span>
                        <input type="text"  placeholder="your name" id ='up' name="user"/>
                        <input type="password"  placeholder="your password" name="pass"/>
                        <input type="password"  placeholder="repeat password" name="spass"/>
                        <input type="submit" id='s' value="sign up">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="design/js/welcom.js"></script>
</body>
</html>
<?php 
}else {
    header('location:home.php');
    exit();
}

?>