<?php
    try {
        require("../script/connexion_bd.php");
        $nom_dossier = '../res/images/produit/';
        $dossier = opendir($nom_dossier);

        $i=0;
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') {
                if($fichier == "readme.txt"){
                    continue;
                }

                $query = $bdd->prepare("SELECT * FROM imageProduit WHERE nomImageProduit=?");
                $query2 = $bdd->prepare("SELECT * FROM produit WHERE imageProduit=?");
                $query->execute(array($fichier));
                $query2->execute(array($fichier));

                ($query->fetch() || $query2->fetch()) ? null : (unlink($nom_dossier . $fichier) ? $i++ : null);
            }
        }
        echo("$i fichiers de produits ont ete supprimer <br>");
        closedir($dossier);
        
        $nom_dossier = '../res/images/marque/';
        $dossier = opendir($nom_dossier);

        $i=0;
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') {
                if($fichier == "readme.txt"){
                    continue;
                }

                $query = $bdd->prepare("SELECT * FROM marque WHERE imageMarque=?");
                $query->execute(array($fichier));

                ($query->fetch()) ? null : (unlink($nom_dossier . $fichier) ? $i++ : null);
            }
        }
        echo("$i fichiers de marques ont ete supprimer");
        closedir($dossier);
    } 
    catch (Exception $e) {
        die($e->getMessage());
    }
?>