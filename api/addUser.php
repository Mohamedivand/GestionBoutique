<?php
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require_once('../class/User.php');
        
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        if(!isset($_COOKIE['idUser'])){
            header('HTTP/1.1 404 Access refuser');
            exit();
        }

        $idUser=$_COOKIE['idUser'];

        $user= new User($bdd, $idUser);

        if($user->getExiste() == false){
            header('HTTP/1.1 404 Access refuser cet utilisateur nexiste pas');
            exit();
        }
        
        if(!in_array($user->getRole()->getRole(), array('admin', 'proprietaire'))){
            header('HTTP/1.1 404 Access refuser. role incorrecte');
            exit();
        }
        
        if(!(isset($_POST['nomUser']) && isset($_POST['prenomUser']) && isset($_POST['login']) && isset($_POST['mdp']) && isset($_POST['idRole']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $nomUser=$_POST['nomUser'];
        $prenomUser=$_POST['prenomUser'];
        $login=$_POST['login'];
        $mdp=$_POST['mdp'];
        $role=new Role($bdd, $_POST['idRole']);
        
        if(!$role->getExiste()){
            header('HTTP/1.1 403 role non autoriser');
            exit();
        }
        
        $idBoutique=(isset($_COOKIE['idBoutique']) ? $_COOKIE['idBoutique'] : null);
        $tel=(isset($_POST['tel']) ? $_POST['tel'] : null);
        $email=(isset($_POST['email']) ? $_POST['email'] : null);
        $adresse=(isset($_POST['adresse']) ? nl2br($_POST['adresse']) : null);
        $whatsapp=(isset($_POST['whatsapp']) ? $_POST['whatsapp'] : null);
        
        if(isset($_POST['idUser'])){
            $user = new User($bdd, $_POST['idUser']);
            if(!$user->getExiste()){
                header("HTTP/1.1 404 utilisateur introuvable");
                exit();
            }

            $tel=(isset($_POST['tel']) ? $_POST['tel'] : $user->getContact()->getTel());
            $email=(isset($_POST['email']) ? $_POST['email'] : $user->getContact()->getEmail());
            $adresse=(isset($_POST['adresse']) ? $_POST['adresse'] : $user->getContact()->getAdresse());
            $whatsapp=(isset($_POST['whatsapp']) ? $_POST['whatsapp'] : $user->getContact()->getWhatsapp());

            if(!$user->editInfo($nomUser, $prenomUser, $login, $mdp, $user->getDateAjoutUser(), $idBoutique, $role->getId(), $tel, $email, $adresse, $whatsapp)){
                header('HTTP/1.1 500 modification echouer');
                exit();
            }

            header('HTTP/1.1 200 modification ok');
            exit();
        }
        else{
            if($role->getRole() != "fournisseur"){
                $user = new User($bdd, false, $login);
                if($user->getExiste()){
                    header('HTTP/1.1 403 Cet utilisateur existe deja');
                    exit();
                }
            }
            else{
                $query = $bdd->prepare("SELECT * FROM user WHERE nomUser= ? AND id_boutique=?");
                $query->execute(array($nomUser, $idBoutique));
                if($query->fetch()){
                    header('HTTP/1.1 403 Cet utilisateur existe deja');
                    exit();
                }
            }

            $query=$bdd->prepare('INSERT INTO contact (tel, email, adresse, whatsapp) VALUES (?,?,?,?)');
            if(!$query->execute(array($tel, $email, $adresse, $whatsapp))){
                header('HTTP/1.1 500 Erreur serveur lors de lajoue du contact');
                exit();
            }
            
            $idContact_tmp = $bdd->lastInsertId();
            echo 1;
    
            $query=$bdd->prepare('INSERT INTO user (nomUser, prenomUser, `login`, mdp, id_contact, id_role, id_boutique) VALUES (?,?,?,?,?,?,?)');
            if($query->execute(array($nomUser, $prenomUser, $login, $mdp, $idContact_tmp, $role->getId(), $idBoutique))){
                header('HTTP/1.1 200 ok');
                exit();
            }
            else{
                header('HTTP/1.1 500 Ajoue echouer');
                exit();
            }
        }
 
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }

    ob_end_flush();

?>