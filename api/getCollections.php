<?php
    ob_start();
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['idCollection']) && !(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idBoutique=(isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
        $idCollection=$_POST['idCollection'];

        require("../class/Collection.php");

        if(is_numeric($idCollection)){
            $collection= new Collection($bdd, $idCollection);

            if(!$collection->getExiste()){
                header("HTTP/1.1 404 collection introuvable");
                exit();
            }
            
            if($collection->getIdBoutique() != $idBoutique){
                header("HTTP/1.1 404 collection introuvable");
                exit();
            }

            echo(json_encode($collection->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idCollection='djessy'){
            $query=$bdd->prepare('SELECT * FROM `collection` WHERE id_boutique=?');
            $query->execute(array($idBoutique));
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $collection= new Collection($bdd, null, $res);
                $response[]= $collection->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun collection trouvable");
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