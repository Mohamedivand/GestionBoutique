<?php
    ob_start();
    require("../script/connexion_bd.php");
    require("../script/apiInfo.php");
    require("../class/User.php");
    if(!checkToken($API_TOKEN, $bdd)){
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }
    
    if(!isset($_COOKIE['idUser'])){
        header('HTTP/1.1 403 Vous netes pas connecter');
        exit();
    }
    $idUser=$_COOKIE['idUser'];
    $user= new User($bdd, $idUser);
    if(!$user->getExiste()){
        header("HTTP/1.1 404 utilisateur introuvable");
        exit();
    }

    if(!in_array($user->getRole()->getRole(), array("admin"))){
        header('HTTP/1.1 403 Vous navez pas le droit.');
        exit();
    }

    $query = $bdd->query('SELECT * FROM user');

    $existe = false;
    while($res = $query->fetch()){
        $existe= true;
        $user= new User($bdd, null, null, null, $res);
        $response[] = $user->getJson_array();
    }

    if($existe){
        echo(json_encode($response));
        header('HTTP/1.1 200 users ok.');
        exit();
    }
    else{
        header('HTTP/1.1 404 users non.');
        exit();
    }
?>