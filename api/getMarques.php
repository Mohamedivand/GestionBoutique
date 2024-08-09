<?php
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['idMarque']) && !(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idBoutique=(isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
        $idMarque=$_POST['idMarque'];

        require("../class/Marque.php");

        if(is_numeric($idMarque)){
            $marque= new Marque($bdd, $idMarque);

            if(!$marque->getExiste()){
                header("HTTP/1.1 404 marque introuvable");
                exit();
            }

            if($marque->getIdBoutique() != $idBoutique){
                header("HTTP/1.1 404 marque introuvable");
                exit();
            }

            echo(json_encode($marque->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idMarque='djessy'){
            $query=$bdd->prepare('SELECT * FROM `marque` WHERE id_boutique=?');
            $query->execute(array($idBoutique));
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $marque= new Marque($bdd, null ,$res);
                $response[]= $marque->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun marque trouvable");
                echo(json_encode(array(null)));
                exit();
            }

            echo(json_encode($response));
            header("HTTP/1.1 200 ok");
            exit();
        }
        
        
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>