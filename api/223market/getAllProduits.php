<?php
ob_start();

try {
    require("../../script/connexion_bd.php");
    require("../../class/Produit.php");

    $query = $bdd->query("SELECT * FROM produit, boutique WHERE produit.id_boutique = boutique.idBoutique AND boutique.isDeprecated = 1");

    while ($res = $query->fetch()) {
        $result[] = (new Produit($bdd, null, $res))->getJson_array();
    }

    if (!isset($result)) {
        header("HTTP/1.1 200 Aucun produit de trouver"); 

        exit(json_encode([]));
    }

    header("HTTP/1.1 200 produit trouvee");
    exit(json_encode($result));
} catch (Exception $e) {
    header("HTTP/1.1 505 Une erreur est survenue lor de la recuperation des produits");
    exit($e->getMessage());
}
