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

    // quantite des produit vendu
    // echo(1);
    $query = $bdd->prepare("SELECT SUM(reduction) AS sumReduction, SUM(montantPayer) AS sumMontant FROM vente WHERE `id_boutique`=? AND total_prix_produit <= montantPayer + reduction");
    $query->execute([$_COOKIE['idBoutique']]);
    $res = $query->fetch();
    $resQuantite = isset($res['sumQuantite']) ? $res['sumQuantite'] : 0;
    $resMontantVente = isset($res['sumMontant']) ? $res['sumMontant'] : 0;
    $resReduction = isset($res['sumReduction']) ? $res['sumReduction'] : 0;

    // echo(2);

    // montant total des ventes
    // $query = $bdd->prepare("SELECT SUM(montantPayer) AS sum FROM vente WHERE id_boutique=?");
    // $query->execute([$_COOKIE['idBoutique']]);
    // $resMontantVente = $query->fetch();
    // $resMontantVente = isset($resMontantVente['sum']) ? $resMontantVente['sum'] : 0;

    // echo(3);

    // total des reductions
    // $query = $bdd->prepare("SELECT SUM(reduction) AS sum FROM vente WHERE id_boutique=?");
    // $query->execute([$_COOKIE['idBoutique']]);
    // $res = $query->fetch();
    // $resReduction = isset($res['sum']) ? $res['sum'] : 0;

    // echo(4);

    // montant apres reduction
    $resMontantVenteApresReduction = $resMontantVente - $resReduction;

    echo (json_encode(
        [
            "quantiteVendu" => $resQuantite,
            "montantVente" => $resMontantVente,
            "reduction" => $resReduction,
            "montantApresReduction" => $resMontantVenteApresReduction
        ]
    ));

    header('HTTP/1.1 200 ok requeste');
    exit();
} catch (Exception $e) {
    echo ($e->getMessage());
    header('HTTP/1.1 500 Erreur serveur');
    exit();
}
