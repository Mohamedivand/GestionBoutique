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

    if (!isset($_POST['idProduit']) && !isset($_GET['idProduit'])) {
        header("HTTP/1.1 403 manque d'info");
        exit();
    }
    $idProduit = isset($_POST['idProduit']) ? $_POST['idProduit'] : $_GET['idProduit'];

    if (is_numeric($idProduit)) {
        $query = $bdd->prepare("SELECT idProduit, nomProduit FROM produit WHERE idProduit=?");
        $query->execute([$idProduit]);

        if (!$res = $query->fetch()) {
            header("HTTP/1.1 404 produit introuvable");
            exit();
        }

        echo (json_encode(
            [
                "idProduit" => $res['idProduit'],
                "nomProduit" => $res['nomProduit']
            ]
        ));
        
        header("HTTP/1.1 200 requete ok");
        exit();
    } elseif ($idProduit = 'djessy') {
        if (!isset($_POST['min'], $_POST['max'])) {
            header("HTTP/1.1 403 manque de limite");
            exit();
        }
        $min = $_POST['min'];
        $max = $_POST['max'];

        $query = $bdd->prepare("SELECT nomProduit FROM produit WHERE id_boutique=? LIMIT $min , $max");
        $query->execute([$_COOKIE['idBoutique']]);
        $existe = false;
        while ($res = $query->fetch()) {
            $existe = true;
            $produit = new Produit($bdd, null, $res);
            $response[] = $produit->getJson_array();
        }
        if (!$existe) {
            header("HTTP/1.1 200 aucun produit trouvable");
            echo (json_encode(array(null)));
            exit();
        }

        echo (json_encode($response));
        header("HTTP/1.1 200 ok");
        exit();
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Erreur serveur');
    exit();
}
ob_end_flush();
