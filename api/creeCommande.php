<?php
    header("Access-Control-Allow-Origin: *");
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/Boutique.php");
        require_once("../class/Contact.php");
        require_once("../class/Commande.php");
        require_once("../class/Produit.php");
        
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        $token_tmp = $_POST['token'];
        
        if($token_tmp != $API_TOKEN){
            $boutique = new Boutique($bdd, null, $token_tmp);
        }
        else{
            if(!isset($_POST['idBoutique'])){
                header("HTTP/1.1 403 manque d'info");
                exit();
            }
            
            $idBoutique = $_POST['idBoutique'];
    
            if($idBoutique == 'djessy'){
                if(!isset($_COOKIE['idBoutique'])){
                    header("HTTP/1.1 403 manque d'info");
                    exit();
                }
                
                $idBoutique = $_COOKIE['idBoutique'];
            }
            
            $boutique = new Boutique($bdd , $idBoutique);
        }
        
        
        if(!$boutique->getExiste()){
            header('HTTP/1.1 404 boutique introuvable');
            exit();
        }

        if(!isset($_POST['listeProduit'], $_POST['tel'])){

            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $listeProduit = $_POST['listeProduit'];
        $tel = $_POST['tel'];
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : null;
        $whatsapp = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : null;
        $typeCommande = isset($_POST['typeCommande']) ? $_POST['typeCommande'] : "det";

        $query = $bdd->prepare("INSERT INTO contact (tel, email, whatsapp, adresse) VALUES (?,?,?,?)");
        if(!$query->execute(array($tel, $email, $whatsapp, $adresse))){
            header('HTTP/1.1 500 Erreur serveur concernant le contact');
            exit();
        }

        $contact = new Contact($bdd, $bdd->lastInsertId());

        $query = $bdd->prepare("INSERT INTO commande (id_contact, id_boutique, typeCommande) VALUES (?,?,?)");
        if(!$query->execute(array($contact->getId(), $boutique->getId(), $typeCommande))){
            header('HTTP/1.1 500 Erreur serveur concernant la commande');
            $contact->delete();
            exit();
        }

        $commande = new Commande($bdd, $bdd->lastInsertId());

        if($commande->ajouterProduit($listeProduit)){
            echo($commande->getId());
            header("HTPP/1.1 200 ok commande");
            exit();
        }
        else{
            header('HTTP/1.1 500 Erreur ajoue produit');
            $commande->supprimer();
            exit();
        }

    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>