<?php
    ob_start();
    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../script/apiInfo.php");
        require_once("../../class/User.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/CarteBancaire.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])){
            header('HTTP/1.1 403 Vous netes pas connecter');
            exit();
        }
        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$user->getExiste() || !$boutique->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }

        if(!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 Vous etes un simple fournisseur');
            exit();
        }

        if(!isset($_POST['numero'], $_POST['solde'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $nomBanque = isset($_POST['nomBanque']) ? $_POST['nomBanque'] : null;

        $numero= $_POST['numero'];
        $solde= is_numeric($_POST['solde']) ? $_POST['solde'] : 0;

        if(isset($_POST['idCarte'])){
            if(!is_numeric($_POST['idCarte'])){
                header('HTTP/1.1 403 carte incorrecte');
                exit();
            }
            $idCarte = $_POST['idCarte'];
            $carte= new CarteBancaire($bdd, $idCarte);

            if(!$carte->getExiste() || $carte->getId_boutique() != $boutique->getId()){
                header('HTTP/1.1 403 cette carte nexiste pas');
                exit();
            }

            if($carte->update($numero, $solde)){
                header('HTTP/1.1 200 maj ok');
            }
            else{
                header('HTTP/1.1 500 Echouer');
            }  
        }
        else{
            $query=$bdd->prepare('SELECT * FROM `carteBancaire` WHERE numeroCarte=? AND id_boutique=? LIMIT 1');
            $query->execute(array($numero, $boutique->getId()));
            if($query->fetch()){
                header('HTTP/1.1 403 cette carte existe deja');
                exit();
            }

            $query=$bdd->prepare('INSERT INTO carteBancaire (numeroCarte, solde, nomBanque, id_boutique) VALUES(?,?,?,?)');
            if($query->execute(array($numero, $solde, $nomBanque, $boutique->getId()))){
                header('HTTP/1.1 200 ajoue Ok');
            }
            else{
                header('HTTP/1.1 500 Echouer');
            }
        }
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>