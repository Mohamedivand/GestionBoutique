<?php
ob_start();
try {
    require("../../script/connexion_bd.php");
    require("../../script/apiInfo.php");
    require("../../class/Boutique.php");
    require("../../class/Vente.php");

    if (!checkToken($API_TOKEN, $bdd)) {
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }

    $token_tmp = $_POST['token'];

    if ($token_tmp != $API_TOKEN) {
        $boutique = new Boutique($bdd, null, $token_tmp);
    } else {
        if (!isset($_POST['idBoutique'], $_POST['min'], $_POST['max'])) {
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idBoutique = $_POST['idBoutique'];

        if ($idBoutique == 'djessy') {
            if (!isset($_COOKIE['idBoutique'])) {
                header("HTTP/1.1 403 manque d'info");
                exit();
            }

            $idBoutique = $_COOKIE['idBoutique'];
        }

        $boutique = new Boutique($bdd, $idBoutique);
    }

    if (!isset($_POST['idBoutique'], $_POST['min'], $_POST['max'])) {
        header("HTTP/1.1 403 manque d'info 2");
        exit();
    }

    $min = $_POST['min'];
    $max = $_POST['max'];

    if (!$boutique->getExiste()) {
        header('HTTP/1.1 404 boutique introuvable');
        exit();
    }
    header("Access-Control-Allow-Origin: *");

    $query = $bdd->prepare("SELECT * FROM vente WHERE id_boutique=? ORDER BY idVente DESC limit $min, $max");
    $query->execute([
        $idBoutique
    ]);

    $existe = false;

    while ($res = $query->fetch()) {
        $existe = true;

        $result[] = (new Vente($bdd, null, $res))->getJson_array();
    }

    if (!$existe) {
        header('HTTP/1.1 404 aucune vente');
        exit();
    }

    echo (json_encode($result));

    header("HTTP/1.1 200 ok boutique");
} catch (Exception $e) {
    echo $e->getMessage();
    header('HTTP/1.1 500 Erreur serveur');
    exit();
}
ob_end_flush();
