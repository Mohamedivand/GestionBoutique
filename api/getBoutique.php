<?php
    ob_start();
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/Boutique.php");
        
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        $token_tmp = $_POST['token'];
        
        if($token_tmp != $API_TOKEN){
            $boutique = new Boutique($bdd, null, $token_tmp);
        }
        else{
            if(!isset($_POST['idBoutique'])){
                header("HTTP/1.1 403 manque d'info");
                exit();
            }
            
            $idBoutique = $_POST['idBoutique'];
    
            if($idBoutique == 'djessy'){
                if(!isset($_COOKIE['idBoutique'])){
                    header("HTTP/1.1 403 manque d'info");
                    exit();
                }
                
                $idBoutique = $_COOKIE['idBoutique'];
            }
            
            $boutique = new Boutique($bdd , $idBoutique);
        }
        
        
        if(!$boutique->getExiste()){
            header('HTTP/1.1 404 boutique introuvable');
            exit();
        }
        header("Access-Control-Allow-Origin: *");

        if(!isset($_POST['action'])){
            header('HTTP/1.1 403 aucune action');
            exit();
        }

        $action = $_POST['action'];

        if($action == 1){
            // on charge les produits
            if(!$boutique->chargerProduit()){
                // echo(null);
                header('HTTP/1.1 200 aucun produit');
                exit();
            }
        }

        elseif($action==2){
            // on charge les fournisseur
            if(!$boutique->chargerFournisseur()){
                header('HTTP/1.1 200 aucun fournisseur');
                exit();
            }
        }
        
        elseif($action==3){
            // on charge les commade
            if(!$boutique->chargerCommande()){
                header('HTTP/1.1 200 aucune commande');
                exit();
            }
        }
        elseif($action==4){
            // on charge les vente
            if(!$boutique->chargerVente()){
                header('HTTP/1.1 200 aucune vente');
                exit();
            }
        }
        elseif($action==5){
            // on charge les depesne
            if(!$boutique->chargerDepense()){
                header('HTTP/1.1 200 aucune depense');
                exit();
            }
        }
        elseif($action==6){
            // on charge les depesne
            if(!$boutique->chargerProformat()){
                header('HTTP/1.1 200 aucun proformat');
                exit();
            }
        }
        elseif($action==7){
            // on charge les transactions de la caisse
            if(!is_null($boutique->getCaisse())){
                if($boutique->getCaisse()->chargerTransactions()){
                    $result = array(
                        "idCaisse" => $boutique->getCaisse()->getId(),
                        "solde" => $boutique->getCaisse()->getSolde(),
                        "sesTransactions" => $boutique->getCaisse()->getTableauTransaction()
                    );
                    echo(json_encode($result));
                    header('HTTP/1.1 200 la liste de vos transations');
                }
                exit();
            }
        }
        elseif($action==8){
            // on charge les depesne
            if(!$boutique->chargerCarteBancaire()){
                header('HTTP/1.1 200 aucune carte bancaire');
                exit();
            }
        }
        elseif($action==9){
            // on charge les depesne
            if(!$boutique->chargerAchat()){
                header('HTTP/1.1 200 aucun achat');
                exit();
            }
        }
        elseif($action==10){
            // on charge les sous traitence
            if(!$boutique->chargerSousTraitence()){
                header('HTTP/1.1 200 aucune sous traitence');
                exit();
            }
        }
        
        echo(json_encode($boutique->getJson_array()));

        header("HTTP/1.1 200 ok boutique");
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>