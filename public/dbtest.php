<?php 
    $pdo = new PDO('mysql:host=sql212.epizy.com;dbname=epiz_32235241_eazyrent;','epiz_32235241','d8jTgtDvUQuB');

    $stmt = $pdo->query('select * from users');
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($res);
?>