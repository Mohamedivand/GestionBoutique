<?php
    ob_start();
    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once("../class/User.php");
        require_once("../class/Boutique.php");
        require_once("../class/Type.php");

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

        if(!isset($_POST['nomType'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $nomType= $_POST['nomType'];

        $descriptionType= (isset($_POST['descriptionType'])) ? $_POST['descriptionType'] : null;

        if(isset($_POST['idType'])){
            if(!is_numeric($_POST['idType'])){
                header('HTTP/1.1 403 type incorrecte');
                exit();
            }
            $idType = $_POST['idType'];
            $type= new Type($bdd, $idType);

            if(!$type->getExiste()){
                header('HTTP/1.1 403 cet type nexiste pas');
                exit();
            }

            if($type->update($nomType, $descriptionType)){
                header('HTTP/1.1 200 ok');
            }
            else{
                header('HTTP/1.1 500 Echouer');
            }  
        }
        else{
            $query=$bdd->prepare('SELECT * FROM `type` WHERE nomType=? AND id_boutique=?');
            $query->execute(array($nomType, $boutique->getId()));
            if($query->fetch()){
                header('HTTP/1.1 403 cet type existe deja');
                exit();
            }

            $query=$bdd->prepare('INSERT INTO `type` (nomType, descriptionType, id_boutique) VALUES(?,?,?)');
            if($query->execute(array($nomType, $descriptionType, $boutique->getId()))){
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