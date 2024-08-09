<?php
    ob_start();
    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once("../class/User.php");
        require_once("../class/Boutique.php");
        require_once("../class/Categorie.php");

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

        if(!isset($_POST['nomCategorie'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $nomCategorie= $_POST['nomCategorie'];

        $descriptionCategorie= (isset($_POST['descriptionCategorie'])) ? $_POST['descriptionCategorie'] : null;

        if(isset($_POST['idCategorie'])){
            if(!is_numeric($_POST['idCategorie'])){
                header('HTTP/1.1 403 type incorrecte');
                exit();
            }
            $idCategorie = $_POST['idCategorie'];
            $categorie= new Categorie($bdd, $idCategorie);

            if(!$categorie->getExiste()){
                header('HTTP/1.1 403 cette categorie nexiste pas');
                exit();
            }

            if($categorie->update($nomCategorie, $descriptionCategorie)){
                header('HTTP/1.1 200 ok');
            }
            else{
                header('HTTP/1.1 500 Echouer');
            }  
        }
        else{
            $query=$bdd->prepare('SELECT * FROM `categorie` WHERE nomCategorie=? AND id_boutique=?');
            $query->execute(array($nomCategorie, $boutique->getId()));
            if($query->fetch()){
                header('HTTP/1.1 403 cette categorie existe deja');
                exit();
            }

            $query=$bdd->prepare('INSERT INTO `categorie` (nomCategorie, descriptionCategorie, id_boutique) VALUES(?,?,?)');
            if($query->execute(array($nomCategorie, $descriptionCategorie, $boutique->getId()))){
                header('HTTP/1.1 200 Ok');
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