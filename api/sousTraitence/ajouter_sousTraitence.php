<?php
    ob_start();

    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../script/apiInfo.php");
        require_once("../../class/User.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/sousTraitence.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset(
            $_COOKIE['idUser'], 
            $_COOKIE['idBoutique'], 
            $_POST['listeProduit'], 
            $_POST['nom'], 
            $_POST['tel']
        )){
            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $listeProduit = $_POST['listeProduit'];
        $reduction = $_POST['reduction'];
        $typeVente = $_POST['typeVente'];
        $nom = $_POST['nom'];
        $tel = $_POST['tel'];
        $email = (isset($_POST['email'])) ? $_POST['email'] : null;
        $adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : null;
        $whatsapp = (isset($_POST['whatsapp'])) ? $_POST['whatsapp'] : null;

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
        
        $query = $bdd->prepare('INSERT INTO contact (tel, email, adresse, whatsapp) VALUES(?,?,?,?)');
        if(!$query->execute(
            array(
                $tel,
                $email, 
                $adresse,
                $whatsapp
            ))
        ){
            header("HTTP/1.1 500 num non ajouter");
            exit();
        }

        $query = $bdd->prepare('INSERT INTO sousTraitence (nomBoutique, id_contact, id_boutique) VALUES(?,?,?)');
        $query->execute(array(
            $nom,
            $bdd->lastInsertId(),
            $idBoutique
        ));

        $newSoustraitence = new SousTraitence($bdd, $bdd->lastInsertId());

        if($newSoustraitence->ajouterProduit($listeProduit)){
            echo($newSoustraitence->getId());
            header("HTTP/1.1 200 ajouter");
            exit();
        }
        else{
            header("HTTP/1.1 500 Une erreur est servenue");
            exit();
        }
    }
    catch(Exception $e){
        header("HTTP/1.1 500 Une erreur est servenue");
        exit();
    }

    ob_end_flush();
?>