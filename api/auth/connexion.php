<?php
    ob_start();
    try{
        require("../../script/connexion_bd.php");
        require("../../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
        }

        if(!isset($_POST['login'], $_POST['mdp'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }
        
        if($_POST['login'] == "" || $_POST['mdp']== ""){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        extract($_POST);

        require("../../class/User.php");
        $user= new User($bdd, false, $login, $mdp);

        if($user->getExiste()){
            setcookie('idUser', $user->getId(), time()+ 3600*24*365, '/');
            setcookie('mdpUser', $user->getMdp(), time()+ 3600*24*365, '/');

            echo(json_encode($user->getJson_array()));
            header('HTTP/1.1 200 Ok');

            $description = "Connexion au site";

            $user->ajouterHistorique($description);
        }
        else{
            header("HTTP/1.1 404 utilisateur introuvable");
            setcookie('idUser', 1, 1, '/');
        }
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        setcookie('idUser', 1, 1, '/');
    }
    ob_end_flush();

?>