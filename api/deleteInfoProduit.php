<?php
    ob_start();
    try{
        require_once('../script/connexion_bd.php');
        require_once("../script/apiInfo.php");
        require_once('../class/User.php');

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Token incorrecte');
            exit();
        }

        if(!isset($_COOKIE['idUser'], $_POST['id'], $_POST['action'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idCurrentUser=$_COOKIE['idUser'];
        $idPost=$_POST['id']; 
        $action=$_POST['action']; 

        $currentUser= new User($bdd, $idCurrentUser);
        

        if(!$currentUser->getExiste()){
            header("HTTP/1.1 403 info introuvable");
            exit();
        }

        if(!in_array($currentUser->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 cet utilisateur nexiste pas');
            exit();
        }

        if($action == 1){
            require_once('../class/Marque.php');
            $object= new Marque($bdd, $idPost);
            if($object->getExiste()){
                if($object->delete()){
                    header('HTTP/1.1 200 supprimer!');
                    exit();
                }
            }
        }

        if($action == 2){
            require_once('../class/Type.php');
            $object= new Type($bdd, $idPost);
            if($object->getExiste()){
                if($object->delete()){
                    header('HTTP/1.1 200 supprimer!');
                    exit();
                }
            }
        }

        if($action == 3){
            require_once('../class/Collection.php');
            $object= new Collection($bdd, $idPost);
            if($object->getExiste()){
                if($object->delete()){
                    header('HTTP/1.1 200 supprimer!');
                    exit();
                }
            }
        }

        if($action == 4){
            require_once('../class/Categorie.php');
            $object= new Categorie($bdd, $idPost);
            if($object->getExiste()){
                if($object->delete()){
                    header('HTTP/1.1 200 supprimer!');
                    exit();
                }
            }
        }

        header('HTTP/1.1 500 suppression echouer');
 
    }
    catch(Exception $e){
        header('HTTP/1.1 500 une erreur est survenue');
    }

    ob_end_flush();
?>