<?php 
require_once('../classes/Authenticate.php');
//use App\Authenticate\Authenticate;
session_start();

 if(! Authenticate::isLoggedIn()){
     header("Location: /login.php");
     exit;
 }
if($_SERVER['REQUEST_METHOD'] != "POST"){
    header("Location: /my-requested-items.php");
    exit;
}

 require_once("../classes/connect.php");
 $sql = "UPDATE items SET ItemStatus = 'AVAILABLE' WHERE ItemId = '".$_POST['itemid']."';";
 $result = $db->query($sql);
 if($result){
     echo "AVAILABLE";
 }else{
     echo "failed !";
 }

?>