<?php
    ob_start();

    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once('../class/User.php');
        require_once('../class/Boutique.php');

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        if(!isset($_COOKIE['idUser'])){
            header('HTTP/1.1 404 Access refuser');
            exit();
        }

        $user= new User($bdd, $_COOKIE['idUser']);
        if(!$user->getExiste() || !in_array($user->getRole()->getRole(), array('admin'))){
            header('HTTP/1.1 404 Access refuser');
            exit();
        }
        
        if(!(isset($_POST['nomBoutique']) && isset($_POST['tel']) && isset($_POST['idUser']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $nomBoutique=$_POST['nomBoutique'];
        $tel=$_POST['tel'];
        $idUser=$_POST['idUser'];

        $user_tmp=new User($bdd, $_POST['idUser']);
        
        if(!$user_tmp->getExiste() || $user_tmp->getRole()->getRole()!='proprietaire'){
            header('HTTP/1.1 403 cet utilisateur nest pas un proprietaire. Veuillez lenregisstrer comme un proprietaire');
            exit();
        }
        
        $date_ajoue_boutique=(isset($_POST['date_ajoue_boutique']) ? $_POST['date_ajoue_boutique'] : null);
        $email=(isset($_POST['email']) ? $_POST['email'] : null);
        $adresse=(isset($_POST['adresse']) ? $_POST['adresse'] : null);
        $whatsapp=(isset($_POST['whatsapp']) ? $_POST['whatsapp'] : null);
        
        if(isset($_POST['idBoutique'])){
            $boutique = new Boutique($bdd, $_POST['idBoutique']);
            if(!$boutique->getExiste()){
                header("HTTP/1.1 404 boutique introuvable");
                exit();
            }

            $email=(isset($_POST['email']) ? $_POST['email'] : $boutique->getContact()->getEmail());
            $adresse=(isset($_POST['adresse']) ? $_POST['adresse'] : $boutique->getContact()->getAdresse());
            $whatsapp=(isset($_POST['whatsapp']) ? $_POST['whatsapp'] : $boutique->getContact()->getWhatsapp());

            if(!$boutique->editInfo($nomBoutique, $boutique->getDebutAbonnement(), $boutique->getFinAbonnement(), $boutique->getDate_ajoue_boutique(), $boutique->getdateModificationBoutique(),$boutique->getStatutBoutique(), $idUser, $tel, $email, $adresse, $whatsapp)){
                header('HTTP/1.1 500 modification echouer');
                exit();
            }

            header('HTTP/1.1 200 modification ok');

            $boutique->ajouterHistorique("Modification des informations de la boutique", $user->getId());
            exit();
        }
        else{
            $query = $bdd->prepare('SELECT * FROM boutique WHERE nomBoutique=? AND id_user=?');
            $query->execute(array($nomBoutique, $idUser));
            if($query->fetch()){
                header('HTTP/1.1 403 cet utilisateur possede deja cette boutique');
                exit();
            }

            $query=$bdd->prepare('INSERT INTO contact (tel, email, adresse, whatsapp) VALUES (?,?,?,?)');
            if(!$query->execute(array($tel, $email, $adresse, $whatsapp))){
                header('HTTP/1.1 500 Erreur serveur lors de lajoue du contact');
                exit();
            }
            
            $idContact_tmp = $bdd->lastInsertId();
            echo 1;
    
            $query=$bdd->prepare('INSERT INTO boutique (nomBoutique, id_user, id_contact_boutique) VALUES (?,?,?)');
            if($query->execute(array($nomBoutique, $idUser, $idContact_tmp))){
                header('HTTP/1.1 200 ok');

                $idBoutique_tmp = $bdd->lastInsertId();
                $boutique= new Boutique($bdd, $idBoutique_tmp);
                echo($user->getId());
                $boutique->ajouterHistorique("Boutique ajouter", $user->getId());
                exit();
            }
            else{
                header('HTTP/1.1 500 Ajoue echouer');
                exit();
            }
        }
 
    }
    catch(Exception $e){
        // header('HTTP/1.1 500 Erreur serveur');
        echo($e->getMessage());
        exit();
    }
    ob_end_flush();

?>