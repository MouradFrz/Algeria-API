<?php

class dbConnect{
    public static function connect(){
        return new PDO('mysql:host=localhost:3310;dbname=algeria-api','root','mourad');
    }
}

?>