<?php
    ob_start();
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['idRole'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idRole=$_POST['idRole'];

        require("../class/Role.php");

        if(is_numeric($idRole)){
            $role= new Role($bdd, $idRole);

            if(!$role->getExiste()){
                header("HTTP/1.1 404 role introuvable");
                exit();
            }

            echo(json_encode($role->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idRole='djessy'){
            $query=$bdd->query('SELECT * FROM `role`');
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $role= new Role($bdd, null, $res);
                $response[]= $role->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun role trouvable");
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