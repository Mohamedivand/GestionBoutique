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

    if(isset($_COOKIE['montantDette'])){
        echo($_COOKIE['montantDette']);
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

    $montantVente = 0;

    foreach($listeIdVente AS $idVente){
        $vente = new Vente($bdd, $idVente['idVente']);

        $dateVente = date('Y-m-d', strtotime($vente->getDateVente()));

        if($vente->getTotal_a_payer() - $vente->getReduction() > $vente->getMontantPayer()){
            $montantVente += ($vente->getTotal_a_payer() - $vente->getReduction() - $vente->getMontantPayer());
        }
    }
     
    setcookie("montantDette", $montantVente, time()+600, "/");

    echo($montantVente);
    header("HTTP/1.1 200 ok Produit");
    exit();
}
catch(Exception $e){
    header("HTTP/1.1 500 non Produit");
    exit();
}
?>