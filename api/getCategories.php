<?php
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/Boutique.php");
        require("../class/Categorie.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        $token_tmp = $_POST['token'];
        
        if($token_tmp != $API_TOKEN){
            $boutique = new Boutique($bdd, null, $token_tmp);
        }
        else{
            if(!(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))){
                header("HTTP/1.1 403 manque id boutique");
                exit();
            }

            $idBoutique=(isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
            $boutique = new Boutique($bdd, $idBoutique);
        }

        if(!$boutique->getExiste()){
            header('HTTP/1.1 404 boutique introuvable');
            exit();
        }
        header("Access-Control-Allow-Origin: *");

        $idBoutique = $boutique->getId();
        
        if(!isset($_POST['idCategorie'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idCategorie=$_POST['idCategorie'];


        if(is_numeric($idCategorie)){
            $categorie= new Categorie($bdd, $idCategorie);

            if(!$categorie->getExiste()){
                header("HTTP/1.1 404 categorie introuvable");
                exit();
            }
            
            if($categorie->getIdBoutique() != $idBoutique){
                header("HTTP/1.1 404 categorie introuvable");
                exit();
            }

            echo(json_encode($categorie->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idCategorie='djessy'){
            $query=$bdd->prepare('SELECT * FROM `categorie` WHERE id_boutique=?');
            $query->execute(array($idBoutique));
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $categorie= new Categorie($bdd, null, $res);
                $response[]= $categorie->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun categorie trouvable");
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