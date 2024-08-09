<?php
    ob_start();
    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once("../class/User.php");
        require_once("../class/Boutique.php");
        require_once("../class/Collection.php");

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

        if(!isset($_POST['nomCollection'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $nomCollection= $_POST['nomCollection'];

        $descriptionCollection= (isset($_POST['descriptionCollection'])) ? $_POST['descriptionCollection'] : null;

        if(isset($_POST['idCollection'])){
            if(!is_numeric($_POST['idCollection'])){
                header('HTTP/1.1 403 type incorrecte');
                exit();
            }
            $idCollection = $_POST['idCollection'];
            $collection= new Collection($bdd, $idCollection);

            if(!$collection->getExiste()){
                header('HTTP/1.1 403 cette collection nexiste pas');
                exit();
            }

            if($collection->update($nomCollection, $descriptionCollection)){
                header('HTTP/1.1 200 ok');
            }
            else{
                header('HTTP/1.1 500 Echouer');
            }  
        }
        else{
            $query=$bdd->prepare('SELECT * FROM `collection` WHERE nomCollection=? AND id_boutique=?');
            $query->execute(array($nomCollection, $boutique->getId()));
            if($query->fetch()){
                header('HTTP/1.1 403 cette collection existe deja');
                exit();
            }

            $query=$bdd->prepare('INSERT INTO `collection` (nomCollection, descriptionCollection, id_boutique) VALUES(?,?,?)');
            if($query->execute(array($nomCollection, $descriptionCollection, $boutique->getId()))){
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