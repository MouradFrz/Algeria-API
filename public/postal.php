<?php
if(!isset($_GET['key']) || !isset($_GET['code']) ){
    echo 'Invalid URL';
    die;
}else{
    $key = $_GET['key'];
    $code = $_GET['code'];
    //getting the list of valid keys
    include_once '../utils/db.php';
    $pdo = dbConnect::connect();
    $stmt = $pdo->prepare('SELECT count(apikey),uses FROM users WHERE apikey=?');
    $stmt->execute([$key]);
    $res= $stmt->fetchAll(PDO::FETCH_ASSOC);
    $valid = $res[0]['count(apikey)'];
    $uses = $res[0]['uses'];
    if(!$valid){
        echo 'Unauthorized API key.';
        die;
    }else{
        if($uses>=1000){
            echo 'You have exceeded the usage limit. Create a new account and use the new API key.';
            die;
        }else{
            $stmt = $pdo->query('SELECT codepostal FROM communes');
            $communes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $communesList=[];
            foreach($communes as $index=>$element){
                $communesList[]=$element['codepostal'];
            }
            if(!in_array($code,$communesList)){
                echo 'invalid postal code.';
                die;
            }else{
                $stmt = $pdo->prepare('SELECT * FROM communes WHERE codepostal=?');
                $stmt->execute([$code]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt = $pdo->prepare('SELECT * FROM wilayas WHERE code=?');
                $stmt->execute([substr($code,0,2)]);
                $result[0]['Wilaya']=$stmt->fetchAll(PDO::FETCH_ASSOC)[0]['name'];
                $result[0]['Wilaya_Nb']=substr($code,0,2);

                $stmt = $pdo->prepare('Select uses from users where apikey=?');
                $stmt->execute([$key]);
                $useCount = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['uses'];


                $stmt = $pdo->prepare('UPDATE users  SET uses = ? WHERE apikey = ?');
                $stmt->execute([$useCount+1,$key]);
                // print_r($stmt) ;
                $pdo=null;

                header('Content-Type: application/json; charset=utf-8');
                print_r(json_encode($result[0]));
            }
        }
    }
}

?>