<?php
try{


    ob_start();
    require_once("../../script/connexion_bd.php");
    require_once("../../class/Vente.php");
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

    if(isset($_COOKIE['quantiteProduitVendu'])){
        echo($_COOKIE['quantiteProduitVendu']);
        header("HTTP/1.1 200 ok Produit");
        exit();
    }

    $currentDate = date('Y-m-d h:i:s');
    $date = date('Y-m-d H:i:s', strtotime($currentDate . ' -8 day'));

    $query = $bdd->prepare('SELECT idVente FROM vente WHERE dateVente>? AND id_boutique=?');
    $query->execute(array($date, $boutique->getId()));

    $existe = false;
    while($res = $query->fetch()){
        $existe =true;
        $listeIdVente[] = $res;
    }

    if(!$existe){
        header("HTTP/1.1 404 aucune vente");
        exit();
    }

    $currentDate = date('Y-m-d');
    $date = date('Y-m-d', strtotime($currentDate . ' -8 day'));
    // echo($date);

    $quantiteProduitVendu = 0;

    foreach($listeIdVente AS $idVente){
        $vente = new Vente($bdd, $idVente['idVente']);

        $query = $bdd->prepare('SELECT SUM(quantiteVenteProduit) AS quantite FROM venteProduit WHERE id_vente=?');
        $query->execute(array($idVente['idVente']));
        if($res = $query->fetch()){
            $quantiteProduitVendu += $res['quantite'];
        }
    }
        
    setcookie("quantiteProduitVendu", $quantiteProduitVendu, time()+600, "/");

    echo($quantiteProduitVendu);
    header("HTTP/1.1 200 ok Produit");
    exit();
}
catch(Exception $e){
    header("HTTP/1.1 500 non Produit");
    exit();
}
?>