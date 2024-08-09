<?php
try{


    ob_start();
    require_once("../../script/connexion_bd.php");
    require_once("../../class/Depense.php");
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

    if(isset($_COOKIE['montantDepense'])){
        echo($_COOKIE['montantDepense']);
        header("HTTP/1.1 200 ok Produit");
        exit();
    }

    $currentDate = date('Y-m-d h:i:s');
    $date = date('Y-m-d H:i:s', strtotime($currentDate . ' -8 day'));

    $query = $bdd->prepare('SELECT idDepense FROM depense WHERE dateDepense>? AND id_boutique=?');
    $query->execute(array($date, $boutique->getId()));

    $existe = false;
    while($res = $query->fetch()){
        $existe =true;
        $listeIdDepense[] = $res;
    }

    if(!$existe){
        header("HTTP/1.1 404 aucune depense");
        exit();
    }

    $currentDate = date('Y-m-d');
    $date = date('Y-m-d', strtotime($currentDate . ' -8 day'));
    // echo($date);

    $montantDepense = 0;

    for($i=1; $i<8; $i++){
        $date = date('Y-m-d', strtotime($date . ' +1 day'));

        foreach($listeIdDepense AS $idDepense){
            $depense = new Depense($bdd, $idDepense['idDepense']);
    
            $dateDepense = date('Y-m-d', strtotime($depense->getDateDepense()));
    
            if($date == $dateDepense){
                $montantDepense += $depense->getMontant();
            }
        }
    }    
    setcookie("montantDepense", $montantDepense, time()+600, "/");

    echo($montantDepense);
    header("HTTP/1.1 200 ok Produit");
    exit();
}
catch(Exception $e){
    header("HTTP/1.1 500 non Produit");
    exit();
}
?>