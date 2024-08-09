<?php
    try {
        ob_start();
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/Boutique.php");
        require("../class/Vente.php");

        if (!checkToken($API_TOKEN, $bdd)) {
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        $token_tmp = $_POST['token'];

        if ($token_tmp != $API_TOKEN) {
            $boutique = new Boutique($bdd, null, $token_tmp);
        } 
        else {
            if (!(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))) {
                header("HTTP/1.1 403 manque id boutique");
                exit();
            }

            $idBoutique = (isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
            $boutique = new Boutique($bdd, $idBoutique);
        }

        if (!$boutique->getExiste()) {
            header('HTTP/1.1 404 boutique introuvable');
            exit();
        }
        header("Access-Control-Allow-Origin: *");

        if (!isset($_POST['idVente'])) {
            header('HTTP/1.1 403 vente introuvable');
            exit();
        }

        $idVente = $_POST['idVente'];
        $vente = new Vente($bdd, $idVente);

        if (!$vente->getExiste()) {
            header('HTTP/1.1 404 vente introuvable');
            exit();
        }

        $vente->chargerPaimement();

        echo(json_encode($vente->getJson_array()));

        header("HTTP/1.1 200 Finit");
    } 
    catch (Exception $e) {
        header('HTTP/1.1 505 erreur');
        exit();
    }

    ob_end_flush();


?>