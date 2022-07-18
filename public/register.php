<?php

function generateKey(){
    $chars='abcdefghijklmnopqrstuvwxyz1234567890';
    $chars_array = str_split($chars);
    $result='';
    for($i=0;$i<=10;$i++){
        $result = $result.$chars_array[rand(0,count($chars_array)-1)];
    }
    return $result;
}

session_start();
if(isset($_SESSION['loggedin'])){
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    $error;
    $succes;

    include '../utils/user.php';

    $user = new User($username, $email, $password, $passwordConfirm);

    if(!$user->notEmpty()){
        $error = "All fields should be filled";
        header('Location: index.php?err=1');
    }
    elseif(!$user->validUsername()){
        $error = "Username Should contain only letters and numbers";
        header('Location: index.php?err=2');
    }
    elseif(!$user->validEmail()){
        $error = "Email format invalid.";
        header('Location: index.php?err=3');
    }
    elseif(!$user->pwMatch()){
        $error = "The passwords don't match";
        header('Location: index.php?err=4');
    }
    elseif(!$user->checkUser()){
        $error = "Username or email already taken";
        header('Location: index.php?err=5');
    }else{
        include '../utils/db.php';
        $pdo = dbConnect::connect();
        $key = generateKey();
        $stmt = $pdo->query('Select apikey from users');
        $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        while(in_array($key,array_column($keys,'apikey'))){
            $key = generateKey();
        }
        $stmt = $pdo->prepare('insert into users (username,email,password,apikey) values(?,?,?,?)');
        $stmt->execute([$user->getusername(),$user->getemail(),password_hash($user->getpassword(),PASSWORD_BCRYPT),$key]);
        $pdo = null;

        $_SESSION['loggedin']=$username;
        header('Location: index.php');
}
}

?>