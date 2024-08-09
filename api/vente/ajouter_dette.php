<?php
    ob_start();

    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../script/apiInfo.php");
        require_once("../../class/User.php");
        require_once("../../class/Produit.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/Role.php");
        require_once("../../class/Contact.php");
        require_once("../../class/Vente.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'], $_POST['listeProduit'], $_POST['typeVente'], $_POST['dateRemboursement'], $_POST['montantPayer'], $_POST['reduction'], $_POST['nom'], $_POST['tel'])){
            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $listeProduit = $_POST['listeProduit'];
        $reduction = $_POST['reduction'];
        $montantPayer = $_POST['montantPayer'];

        $dateRemboursement =$_POST['dateRemboursement']." ";

        $typeVente = $_POST['typeVente'];
        $nom = $_POST['nom'];
        $tel = $_POST['tel'];

        $query = $bdd->prepare('SELECT * FROM `role` WHERE nomRole=?');
        $query->execute(array("client"));
        if(!($res=$query->fetch())){
            header('HTTP/1.1 404 role non');
            exit();
        }

        $roleClient = new Role($bdd, null, $res);

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
        $sum_total_produit = 0;

        foreach($listeProduit as $produit_tmp){
            $leProduit = new Produit($bdd , $produit_tmp['idProduit']);

            if (
                isset($produit_tmp['prixPersonnel']) &&
                is_numeric($produit_tmp['prixPersonnel'])
            ) {
                $sum_total_produit += $produit_tmp['prixPersonnel'];
            } else {
                $sum_total_produit += (($typeVente == "det") ? $leProduit->getPrixVenteDetail() : $leProduit->getPrixVenteEngros()) * $produit_tmp['quantite'];
            }

            if(!$leProduit->getExiste()){
                $produit=null;
                break;
            }

            if($leProduit->getIdBoutique() != $boutique->getId()){
                $produit=null;
                break;
            }

            if($leProduit->getQuantite() < $produit_tmp['quantite']){
                $produit=null;
                break;
            }

            $produit[] = array(
                "produit" => $leProduit,
                "prixPersonnel" => $produit_tmp['prixPersonnel'],
                "quantite" => $produit_tmp['quantite']
            );

        }

        if(is_null($produit)){
            header("HTTP/1.1 404 Donnez des produits qui vous appartienne svp");
            exit();
        }

        $query = $bdd->prepare('SELECT * FROM contact, user WHERE tel=? AND user.id_contact=contact.idContact');
        $query->execute(array($tel));
        if(!($res=$query->fetch())){
            $query = $bdd->prepare('INSERT INTO contact (tel) VALUES(?)');
            if(!$query->execute(array($tel))){
                header("HTTP/1.1 500 num non ajouter");
                exit();
            }
    
            $newContact = new Contact($bdd, $bdd->lastInsertId());
            
            $query = $bdd->prepare('INSERT INTO user (nomUser, prenomUser, id_contact, id_role) VALUES(?,?,?,?)');
            
            if(!$query->execute(
                array(
                    $nom, 
                    $nom, 
                    $newContact->getId(), 
                    $roleClient->getId()
                )
            )){
                $newContact->delete();
    
                header("HTTP/1.1 500 user non ajouter");
                exit();
            }

            $newClient = new User($bdd, $bdd->lastInsertId());
        }
        else{
            $newClient = new User($bdd, $res['idUser']);
        }

        $query = $bdd->prepare('INSERT INTO vente (reduction, montantPayer, total_prix_produit, typeVente, dateRemboursement, id_boutique, id_user) VALUES(?,?,?,?,?,?,?)');
        $query->execute(array(
            $reduction,
            $montantPayer,
            0,
            $typeVente,
            $dateRemboursement,
            $boutique->getId(),
            $newClient->getId()
        ));

        $newVente = new Vente($bdd, $bdd->lastInsertId());

        if($newVente->ajouterProduit($listeProduit)){
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

?>