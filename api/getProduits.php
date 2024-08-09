<?php
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/Produit.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        header("Access-Control-Allow-Origin: *");
        
        if(!isset($_POST['idProduit'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idProduit=$_POST['idProduit'];

        if(is_numeric($idProduit)){
            $produit= new Produit($bdd, $idProduit);

            if(!$produit->getExiste()){
                header("HTTP/1.1 404 marque introuvable");
                exit();
            }

            echo(json_encode($produit->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        elseif($idProduit='djessy'){
            $query=$bdd->query('SELECT iProduit FROM `produit`');
            $existe= false;
            while($res=$query->fetch()){
                $existe=true;
                $produit= new Produit($bdd, $res['iProduit']);
                $response[]= $produit->getJson_array();
                
            }
            if(!$existe){
                header("HTTP/1.1 200 aucun produit trouvable");
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