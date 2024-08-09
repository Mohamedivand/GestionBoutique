<?php
ob_start();

try {
    require("../../script/connexion_bd.php");
    require("../../script/apiInfo.php");
    require("../../class/Produit.php");

    if (!checkToken($API_TOKEN, $bdd)) {
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }

    header("Access-Control-Allow-Origin: *");

    $idBoutique = $_COOKIE['idBoutique'];

    $query = $bdd->prepare("SELECT 
        SUM(quantiteProduit) AS sumQuantiteProduit, 
        SUM(prixAchat * quantiteProduit) AS sumPrixAchat , 
        SUM(prixVenteDetail * quantiteProduit) AS sumPrixVenteDetail, 
        SUM(prixVenteEngros * quantiteProduit) AS sumPrixVenteEngros 
        FROM produit WHERE id_boutique=?
    ");
    $query->execute([$_COOKIE['idBoutique']]);
    $res = $query->fetch();
    $quantiteProduit = (isset($res['sum']) ? $res['sum'] : 0);

    extract($res);

    echo (json_encode(
        [
            "sumQuantiteProduit" => isset($sumQuantiteProduit) ? $sumQuantiteProduit : 0,
            "sumPrixAchat" => isset($sumQuantiteProduit) ? $sumPrixAchat : 0,
            "sumPrixVenteDetail" => isset($sumPrixVenteDetail) ? $sumPrixVenteDetail : 0,
            "sumPrixVenteEngros" => isset($sumPrixVenteEngros) ? $sumPrixVenteEngros : 0
        ]
    ));

    header('HTTP/1.1 200 ok requeste');
    exit();
} catch (Exception $e) {
    echo ($e->getMessage());
    header('HTTP/1.1 500 Erreur serveur');
    exit();
}
