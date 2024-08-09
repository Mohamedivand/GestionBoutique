<?php
    ob_start();

    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../script/apiInfo.php");
        require_once("../../class/User.php");
        require_once("../../class/Produit.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/Achat.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset(
            $_COOKIE['idUser'], 
            $_COOKIE['idBoutique'], 
            $_POST['listeProduit']
        )){
            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $listeProduit = $_POST['listeProduit'];

        $user= new User($bdd, $idUser);

        if(!$user->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }
        
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$boutique->getExiste()){
            header("HTTP/1.1 404 boutique introuvable");
            exit();
        }

        if(!is_array($listeProduit)){
            header("HTTP/1.1 404 Donnez des produits svp");
            exit();
        }

        if(!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 Vous navez pas le droit.');
            exit();
        }

        if(in_array($user->getRole()->getRole(), array("proprietaire")) && $boutique->getProprietaire()->getId() != $user->getId()){
            header('HTTP/1.1 403 Vous navez pas le droit mr.');
            exit();
        }
        
        $produit = null;

        foreach($listeProduit as $produit_tmp){
            $leProduit = new Produit($bdd , $produit_tmp['idProduit']);

            if(!$leProduit->getExiste()){
                $produit=null;
                break;
            }

            if($leProduit->getIdBoutique() != $boutique->getId()){
                $produit=null;
                break;
            }

            if($leProduit->getQuantiteEntrepot() < $produit_tmp['quantite']){
                $produit=null;
                break;
            }

            $produit[] = array(
                "produit" => $leProduit,
                "quantite" => $produit_tmp['quantite']
            );

        }

        if(is_null($produit)){
            header("HTTP/1.1 404 Donnez des produits qui vous appartienne svp");
            exit();
        }

        $query = $bdd->prepare('INSERT INTO achat (id_boutique) VALUES(?)');
        $query->execute(array(
            $boutique->getId(),
        ));

        $newAchat = new Achat($bdd, $bdd->lastInsertId());

        if($newAchat->ajouterProduit($listeProduit)){
            echo($newAchat->getId());
            header("HTTP/1.1 200 ajouter");
            exit();
        }
        else{
            header("HTTP/1.1 500 Une erreur est servenue 1");
            exit();
        }
    }
    catch(Exception $e){
        header("HTTP/1.1 500 Une erreur est servenue");
        exit();
    }

    ob_end_flush();
?>