<?php
    ob_start();
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['idType']) && !(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idBoutique=(isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
        $idType=$_POST['idType'];

        require("../class/Type.php");

        if(is_numeric($idType)){
            $type= new Type($bdd, $idType);

            if(!$type->getExiste()){
                header("HTTP/1.1 404 type introuvable");
                exit();
            }
            
            if($type->getIdBoutique() != $idBoutique){
                header("HTTP/1.1 404 type introuvable");
                exit();
            }

            echo(json_encode($type->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idType='djessy'){
            $query=$bdd->prepare('SELECT * FROM `type` WHERE id_boutique=?');
            $query->execute(array($idBoutique));
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $type= new Type($bdd, null, $res);
                $response[]= $type->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun type trouvable");
                echo(json_encode(array(null)));
                exit();
            }

            echo(json_encode($response));
            header("HTTP/1.1 200 ok");
            exit();
        }
        
        
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>