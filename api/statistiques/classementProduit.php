<?php
    ob_start();
    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../class/Vente.php");
        require_once("../../class/Produit.php");
        require_once("../../class/Boutique.php");

        if(!isset($_COOKIE['idBoutique'])){
            header("HTTP/1.1 403 aucune boutique selectionne");
            exit();
        }

        $boutique = new Boutique($bdd, $_COOKIE['idBoutique']);

        if(!$boutique->getExiste()){
            header("HTTP/1.1 404 aucune boutique");
            exit();
        }

        if(isset($_COOKIE['chart1'])){
            $data = json_decode($_COOKIE['chart1'], true);
            echo(json_encode($data));
            header("HTTP/1.1 200 ok Produit");
            exit();
        }

        $query = $bdd->prepare("SELECT SUM(quantiteVenteProduit) AS nbr, id_produit FROM `venteProduit`, vente WHERE vente.id_boutique=? AND venteProduit.id_vente=vente.idVente GROUP BY id_produit ORDER BY nbr DESC;");
        $query->execute(array($boutique->getId()));

        $existe = false;
        while($res = $query->fetch()){
            $existe = true;

            $liste_idProduit[] = $res;
        }

        if(!$existe){
            header("HTTP/1.1 404 aucun produit");
            exit();
        }

        foreach($liste_idProduit AS $idProduit){
            $produit = new Produit($bdd, $idProduit['id_produit']);

            $nomProduit = $produit->getNomProduit();
            $quantite = 0;
            $montantVendu = 0;
            $benefice = 0;

            $query = $bdd->prepare("SELECT * from vente, venteProduit WHERE venteProduit.id_vente=vente.idVente AND venteProduit.id_produit=?;");
            $query->execute(array($produit->getId()));
            
            $existe = false;

            while($res = $query->fetch()){
                $quantite += $res['quantiteVenteProduit'];
                $montantVendu += (($res['typeVente'] == "det") ? $produit->getPrixVenteDetail() : $produit->getPrixVenteEngros()) * $res['quantiteVenteProduit'];
                $benefice += ((($res['typeVente'] == "det") ? $produit->getPrixVenteDetail() : $produit->getPrixVenteEngros()) - $produit->getPrixAchat()) * $res['quantiteVenteProduit'];
            }

            $listeFinal[] = array(
                "nomProduit" => $nomProduit,
                "quantite" => $quantite,
                "montantVendu" => $montantVendu,
                "benefice" => $benefice
            );

        }
        setcookie("chart1", json_encode($listeFinal), time()+600, "/");

        echo(json_encode($listeFinal));
        header("HTTP/1.1 200 ok Produit");
        exit();
    }
    catch(Exception $e){
        header("HTTP/1.1 500 non Produit");
    exit();
    }

?>