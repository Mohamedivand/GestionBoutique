<?php
ob_start();

try {
    require("../../script/connexion_bd.php");
    require("../../class/Categorie.php");

    $query = $bdd->query("SELECT * FROM categorie, boutique WHERE categorie.id_boutique = boutique.idBoutique AND boutique.isDeprecated = 1");

    while ($res = $query->fetch()) {
        $result[] = (new categorie($bdd, null, $res))->getJson_array();
    }

    if (!isset($result)) {
        header("HTTP/1.1 200 Aucun categorie de trouver"); 

        exit(json_encode([]));
    }

    header("HTTP/1.1 200 categorie trouvee");
    exit(json_encode($result));
} catch (Exception $e) {
    header("HTTP/1.1 505 Une erreur est survenue lor de la recuperation des categories");
    exit($e->getMessage());
}
