<?php
session_start();
include 'init.php';
if (isset($_SESSION['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bni-bchire messanger</title>
    <link rel="stylesheet" href="design/css/font-awesome.min.css">
    <link rel="stylesheet" href="design/css/home.css">
</head>
<body>
    <!-- notifications area  -->
    <b> created BY MOAD ABDOU fb:BymoadmoadbBy</b>
    <div class="container">
        <div class="noti">
            <div class="fr-request" >  
                <form action="" method="post" enctype=""></form>
                <div class="count"></div>                            
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </div>
            <div class="messages">                  
                <div class="count"></div>              
                <i class="fa fa-comment" aria-hidden="true"></i>
            </div>
            <div class="others">        
                <i class="fa fa-users" aria-hidden="true"></i>
            </div>
       </div>
       <div class="hello">
            <?php 
                if($_SERVER['REQUEST_METHOD'] == 'POST' ){
                    if(isset($_FILES['image'])){
                        $img = $_FILES['image'];
                        if($img['error']!=4){
                            $name = $img['name'];
                            $tmp = $img['tmp_name'];
                            $size = $img['size'];
                            $errors =[];
                            $allow_type = array('png', 'jpeg', 'jpg');
                            $r = explode('.',$name);
                            $type= strtolower(end($r));
                            if(!in_array($type, $allow_type)){
                                $errors[] = 'its not image';
                            };
                            if($size > 1000000 ){
                                $errors[]= "can't image be more than 1 mb";
                            };
                            if(empty($errors)){
                                $imgname = rand(0, 1000000000).'.'.$type;
                                move_uploaded_file($tmp ,__DIR__.'/users_imgs/'.$imgname);
                                $con= $db->prepare('UPDATE users SET img=? WHERE user_id =?');
                                $con->execute(array(
                                    $imgname,
                                    $_SESSION['id']
                                ));
                                if($_SESSION['img'] !=  'user1.png' && is_file(__DIR__.'/users_imgs/'.$_SESSION['img'])){
                                    unlink(__DIR__.'/users_imgs/'.$_SESSION['img']);
                                }
                                $_SESSION['img']= $imgname;
                            }
                        }
                    }
                }

            ?>
            <div class="user-image" style='background-image: url("users_imgs/<?php echo $_SESSION['img'] ?>")'>
            </div>
            <span>hello <b><?php echo $_SESSION['name']?></b></span>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                    <div class="file">
                        <span>change image  <sub>< 1mb</sub> </span>
                        <input type="file" name="image" >
                    </div>   
                    <input type="submit" value="save it">
            </form>
       </div>
       <a href="exit.php">
            <div class="sgs">       
                    <i class="fa fa-power-off" aria-hidden="true"></i>
            </div>
       </a>
       <div class="search">
           <input type="search" name="ser" />
           <div class="icon">   
                <i class="fa fa-search" aria-hidden="true"></i>
           </div>    
           <div class="res">
                
           </div>
       </div>
       <div class="move">
           <div class="fr-request">
            <div class="icon">                
                <i class="fa fa-location-arrow" aria-hidden="true"></i>  
            </div>
            <div class="users">
            </div>
            <div class="more">               
                <i class="fa fa-plus-square-o" aria-hidden="true"></i>
            </div>
            <div class="sent">
                <div class="users">
                </div>
            </div>
           </div>
           <div class="messages">
                <div class="icon">                
                    <i class="fa fa-bullseye" aria-hidden="true"></i>   
                </div>
                <div class="users">
                </div>
                <div class="more">               
                    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                </div>
                <div class="actives">
                    <div class="users">
                    </div>
                </div>
           </div>
           <div class="others">
            <div class="users">
            </div>
            <div class="more">               
                <i class="fa fa-plus-square-o" aria-hidden="true"></i>
            </div>
           </div>
       </div>
       <div class="msg-box">
           <div class="info">
               <div class="img">
                    
               </div>
               <span class='name'></span>
               <i class="fa fa-chevron-right" aria-hidden="true"></i>
           </div>
           <div class="msgs">
             <b>no messages</b>
           </div>
           <div class="showmore">
                    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
           </div>
           <div class="input">
               <input type="text" name="msg" />
               <div class="icon">  
                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
               </div>
           </div>
       </div>
       <div class="profile">
           <div class="img">
               <img src="" alt="">
           </div>
           <span></span>
           <div class="controle">
               <span></span>
               <span></span>
           </div>
       </div>
    </div>
    
   <script src="design/js/home.js"></script>
</body>
</html>
<?php
}else {
    re();
}
?>