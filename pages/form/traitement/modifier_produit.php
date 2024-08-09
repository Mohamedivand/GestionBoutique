<?php
    ob_start();
    try{
        // On verifie lutilisateur et la boutique
        require_once("../../../script/connexion_bd.php");
        require_once("../../../class/User.php");
        require_once("../../../class/Boutique.php");
        require_once("../../../class/Produit.php");

        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'], $_POST['idProduit'])){
            header('location:../dashboard/produit.php?popUpText=3');
            exit();
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $idProduit=$_POST['idProduit'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);
        $produit= new Produit($bdd, $idProduit);

        if(!$user->getExiste() || !in_array($user->getRole()->getRole() , array("admin", "proprietaire"))){
            header('location:../dashboard/produit.php?popUpText=5');
            exit();
        }

        if(!$boutique->getExiste()){
            header('location:../dashboard/produit.php?popUpText=4');
            exit();
        }
        
        if(!$produit->getExiste()){
            header('location:../dashboard/produit.php?popUpText=10');
            exit();
        }

        if($produit->getIdBoutique() != $boutique->getId()){
            header('location:../dashboard/produit.php?popUpText=10');
            exit();
        }

        if(!isset($_POST['nom'], $_POST['prixAchat'], $_POST['prixVenteDetail'], $_POST['prixVenteEngros'], $_POST['quantite'], $_POST['description'])){
            header("location:../modifier_produit.php?popUpText=6");
            exit();
        }
        $nom=$_POST['nom'];
        $prixAchat=$_POST['prixAchat'];
        $prixVenteDetail=$_POST['prixVenteDetail'];
        $prixVenteEngros=$_POST['prixVenteEngros'];
        $quantite=$_POST['quantite'];
        $quantiteEntrepot=$_POST['quantiteEntrepot'];
        $description=$_POST['description'];
        $codeBar=(isset( $_POST['codeBar'])) ? $_POST['codeBar'] : null;

        $type=($_POST['type'] == "aucun")? null : $_POST['type'];
        $categorie=($_POST['categorie'] == "aucun")? null : $_POST['categorie'];
        $collection=($_POST['collection'] == "aucun")? null : $_POST['collection'];
        $marque=($_POST['marque'] == "aucun")? null : $_POST['marque'];
        $fournisseur=($_POST['fournisseur'] == "aucun")? null : $_POST['fournisseur'];

        $destination = "../../../res/images/produit/"; 
        $allowTypes = array('jpg','png','jpeg','gif', 'heivc', 'webp'); 
                
        $fileNames = array_filter($_FILES['files']['name']); 

        if(!empty($fileNames)){ 
            $produit_ajouter=false;

            foreach($_FILES['files']['name'] as $key=>$val){ 
                $extension = pathinfo( $_FILES["files"]["name"][$key], PATHINFO_EXTENSION ); // jpg
                        
                if(in_array($extension, $allowTypes)){
                    /* create new name file */
                    $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
                    $basename = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg
                    $source = $_FILES["files"]["tmp_name"][$key];
                    $path = $destination.$basename;
    
                    /* move the file */
                    if(move_uploaded_file($source, $path )){
                        if(!$produit_ajouter){
                            if($produit->getImageProduit()){
                                if(!unlink($destination.$produit->getImageProduit())){
                                    echo("image principlae pas supprimer. ");
                                }
                            }
                            if(!$produit->update(
                                $nom, 
                                $prixAchat, 
                                $prixVenteDetail, 
                                $prixVenteEngros, 
                                $basename, 
                                $quantite, 
                                $quantiteEntrepot, 
                                $description,
                                $categorie,
                                $type,
                                $marque,
                                $collection,
                                $fournisseur ,
                                $codeBar
                            )){
                                header('location:../modifier_produit.php?popUpText=9');
                                break;
                            }
                            echo("Ligne produit cree");

                            $query=$bdd->prepare('SELECT * FROM imageProduit WHERE id_produit=?');
                            $query->execute(array($idProduit));
                            while($res=$query->fetch()){
                                try{
                                    if(!unlink($destination.$res['nomImageProduit'])){
                                        echo("Certain fichier nont pas ete supprimer");
                                    }
                                    $supressionQuery= $bdd->prepare('DELETE FROM imageProduit WHERE idImageProduit=?');
                                    $supressionQuery->execute(array($res['idImageProduit']));
                                }
                                catch(Exception $e){
                                    echo("Certain fichier nont pas ete supprimer");
                                }
                            }

                            $produit_ajouter=true;
                        }
                        else{
                            $query=$bdd->prepare('INSERT INTO imageProduit (`nomImageProduit`, id_produit) VALUES(?,?)');
                            $query->execute(array($basename, $idProduit));
                            echo("ok");
                        }
                    }
                    else{
                        echo("non");
                    }  
                }
            }

            header('location:../modifier_produit.php?popUpText=11');
            exit();


        }
        else{ 
            if(!$produit->update(
                $nom, 
                $prixAchat, 
                $prixVenteDetail, 
                $prixVenteEngros, 
                $produit->getImageProduit(), 
                $quantite, 
                $quantiteEntrepot, 
                $description,
                $categorie,
                $type,
                $marque,
                $collection,
                $fournisseur ,
                $codeBar
            )){
                header('location:../modifier_produit.php?popUpText=9');
            }
            else{
                echo("Ligne produit cree");
    
                header('location:../modifier_produit.php?popUpText=11');
            }
        } 

        exit();
    }
    catch(Exception $e){
        echo($e->getMessage());
        header('location:../modifier_produit.php?popUpText=9');
        exit();
    }


?>