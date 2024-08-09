<?php
ob_start();

try {
    require("../../script/connexion_bd.php");
    require("../../class/Boutique.php");

    $query = $bdd->query("SELECT * FROM boutique WHERE isDeprecated = 1");

    while ($res = $query->fetch()) {
        $result[] = (new Boutique($bdd, null, false, $res))->getJson_array();
    }

    if (!isset($result)) {
        header("HTTP/1.1 200 Aucune boutique de trouver");

        exit(
            json_encode([])
        );
    }

    header("HTTP/1.1 200 boutique trouvee");
    exit(json_encode($result));

} catch (Exception $e) {
    header("HTTP/1.1 505 Une erreur est survenue lor de la recuperation des boutiques");
    exit;
}
