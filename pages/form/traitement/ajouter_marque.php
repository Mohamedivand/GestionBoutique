<?php
    ob_start();
    try{
        require_once("../../../script/connexion_bd.php");
        require_once("../../../class/User.php");
        require_once("../../../class/Boutique.php");

        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])){
            echo("pas de cookie");
            header("location: ../marque.php?popUpText=3");
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$user->getExiste()){
            echo("Cet urilisateur n'existe pas");
            header("location: ../marque.php?popUpText=3");
        }
        if(!$boutique->getExiste()){
            echo("Cette boutique n'existe pas");
            header("location: ../marque.php?popUpText=4");
        }

        if(!in_array($user->getRole()->getRole(), array('admin'))){
            echo("Vous netes pas autoriser");
            header("location: ../marque.php?popUpText=5");
        }

        if(!isset($_POST['nomMarque'])){
            header("location: ../marque.php?popUpText=6");
        }

        $marque=$_POST['nomMarque'];

        $query=$bdd->prepare('SELECT * FROM marque WHERE nomMarque=? AND id_boutique=?');

        $query->execute(array($marque, $boutique->getId()));

        if(!$query->fetch()){
            echo("Marque exist deja");
            header("location: ../marque.php?popUpText=18");
        }

        $destination = "../../../res/images/marque/"; 
        $allowTypes = array('jpg','png','jpeg','gif', 'heivc', 'webp'); 

        if(isset($_FILES['files'])){
            $extension = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
                    
            if(in_array($extension, $allowTypes)){
                /* create new name file */
                $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
                $basename = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg
                $source = $_FILES["files"]["tmp_name"];
                $path = $destination.$basename;

                // /* move the file
                if(move_uploaded_file( $source, $path )){
                    $query=$bdd->prepare('INSERT INTO marque (nomMarque,imageMarque, id_boutique) VALUES (?,?,?)');
                    if($query->execute(array($marque ,$basename,$boutique->getId()))){
                        echo("ok");
                        header("location: ../marque.php?popUpText=12");
                    }
                    else{
                        echo("non");
                        header("location: ../marque.php?popUpText=9");
                    }
                }
                else{
                    echo('Ajoue impossible.'); 
                    header("location: ../marque.php?popUpText=13");
                }  
            }
            else{
                header("location: ../marque.php?popUpText=14");
            }
        }
        else{ 
            $query=$bdd->prepare('INSERT INTO marque (nomMarque, id_boutique) VALUES (?,?)');
            if($query->execute(array($marque, $boutique->getId()))){
                echo("ok");
                header("location: ../marque.php?popUpText=12");
            }
            else{
                echo("non");
                header("location: ../marque.php?popUpText=9");
            }
        } 
    }
    catch(Exception $e){
        $e->getMessage();
        header("location: ../marque.php?popUpText=7");
    }
    ob_end_flush();
?>