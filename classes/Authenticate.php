<?php

//declare(strict_types=1);
//namespace App\Authenticate;

class Authenticate{

    public static function IsLoggedIn(){
        return (!empty($_SESSION['identity']));
    }

public static function generateCSRFToken(){
    if(empty($_SESSION['token'])){
        $_SESSION['token']=bin2hex(rand(1,9999));
    }
}

public static function login(){
    $errors = [];

    if (empty($_POST['username'])){
        $errors['username'] = 'Username is missing';
    }
    if (empty($_POST['password'])){
        $errors['password'] = 'Password is missing';
    }
    if (empty($_POST['__csrf'])){
        $errors['__csrf'] = 'CSRF token is missing';
    }
    if(! ($_SESSION['token']==$_POST['__csrf'])){
        $errors['__csrf'] = 'CSRF token is missing';
    }
    if(!empty($errors)){
        return $errors;
    }
    require_once("connect.php");
    $myusername = mysqli_real_escape_string($db,$_POST['username']);
    $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
    $stmt = $db->prepare("SELECT User_ID, Name, Location FROM logintable WHERE email = ? and pass = ?;");
	$stmt->bind_param("ss",$myusername,$mypassword);
	$stmt->execute();
    $result = $stmt->get_result();
    $count = mysqli_num_rows($result);
    
	if( $count == 1){
          $row = $result->fetch_row();
          $_SESSION['identity'] = $row['0'];
          $_SESSION['username'] = $row['1'];
          $_SESSION['location'] = $row['2'];

		  return $errors;  
	  }else{
			 
		$errors['match'] = 'Email or Password is incorrect!';
	  }

    //check user for login from db
    return $errors;
}

public static function getFormFieldValue($fieldname){
    return (!empty($_POST[$fieldname]))?$_POST[$fieldname]:'';
}

public static function logout(){
    $_SESSION = [];
    session_destroy();
    $_SESSION['token']=bin2hex(rand(1,9999));
}
}
?>