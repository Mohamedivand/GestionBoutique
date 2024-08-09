<?php
    ob_start();
    try{
        require_once("../../../script/connexion_bd.php");
        require_once("../../../class/User.php");
        require_once("../../../class/Boutique.php");

        if(!isset($_COOKIE['idUser'], $_POST['idBoutique'])){
            echo("pas de cookie");
            header("location: ../ajouter_image_boutique.php?popUpText=3");
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_POST['idBoutique'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$user->getExiste()){
            echo("Cet urilisateur n'existe pas");
            header("location: ../ajouter_image_boutique.php?popUpText=3");
        }
        if(!$boutique->getExiste()){
            echo("Cette boutique n'existe pas");
            header("location: ../ajouter_image_boutique.php?popUpText=4");
        }

        if(!in_array($user->getRole()->getRole(), array('admin', 'proprietaire'))){
            echo("Vous netes pas autoriser");
            header("location: ../ajouter_image_boutique.php?popUpText=5");
        }

        $allowTypes = array('jpg','png','jpeg','gif', 'heivc', 'webp', 'svg', 'JPG','PNG','JPEG','GIF', 'HEIVC', 'WEBP', 'SVG'); 

        if(!isset($_FILES['imageTampon']) || !isset($_FILES['imageBanderole'])){
            echo("Envoyez au moin une images");
            header("location: ../ajouter_image_boutique.php?popUpText=5");
        }

        if(isset($_FILES['imageTampon'])){
            $existeTampon = true;
        }

        if(isset($_FILES['imageBanderole'])){
            $existeBanderole = true;
        }

        $footerTexte = (isset($_POST['footerTexte']) ? $_POST['footerTexte'] : $boutique->getTextFooterPDF());


        $tampon = null;
        $banderole = null;
         
        if($existeTampon){
            $extension = pathinfo( $_FILES["imageTampon"]["name"], PATHINFO_EXTENSION ); // jpg
            
            if(in_array($extension, $allowTypes)){
                /* create new name file */
                $destination = "../../../res/images/tampon/"; 
                $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
                $basename = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg
                $source = $_FILES["imageTampon"]["tmp_name"];
                $path = $destination.$basename;
    
                // /* move the file
                $tampon = (move_uploaded_file( $source, $path )) ? $basename : null;
            }
            else{
                echo("L'extension du tamponn est incorrecte");
                header("location: ../ajouter_image_boutique.php?popUpText=14");
            }
        }
        if($existeBanderole){
            $extension = pathinfo( $_FILES["imageBanderole"]["name"], PATHINFO_EXTENSION ); // jpg
            
            if(in_array($extension, $allowTypes)){
                /* create new name file */
                $destination = "../../../res/images/banderole/"; 
                $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
                $basename = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg
                $source = $_FILES["imageBanderole"]["tmp_name"];
                $path = $destination.$basename;
    
                // /* move the file
                $banderole = (move_uploaded_file( $source, $path )) ? $basename : null;
            }
            else{
                echo("L'extension du tamponn est incorrecte");
                header("location: ../ajouter_image_boutique.php?popUpText=14");
            }
        }

        if($boutique->setImage($tampon, $banderole, $footerTexte)){
            echo("Modifier");
            header("location: ../ajouter_image_boutique.php?popUpText=14");
        }
    }
    catch(Exception $e){
        $e->getMessage();
        header("location: ../ajouter_image_boutique.php?popUpText=7");
    }
    ob_end_flush();
?>