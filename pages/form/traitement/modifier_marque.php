<?php
    ob_start();
    try{
        require("../../../script/connexion_bd.php");
        require("../../../class/User.php");
        require("../../../class/Marque.php");

        if(!isset($_COOKIE['idUser'])){
            echo("pas de cookie");
            header("location:../modifierMarque.php?popUpText=3");
        }

        $idUser=$_COOKIE['idUser'];

        $user= new User($bdd, $idUser);

        if(!$user->getExiste()){
            echo("Cet urilisateur n'existe pas");
            header("location:../modifierMarque.php?popUpText=3");
        }

        if(!in_array($user->getRole()->getRole(), array('admin'))){
            echo("Vous netes pas autoriser");
            header("location:../modifierMarque.php?popUpText=5");
        }

        if(!isset($_POST['idMarque'], $_POST['nomMarque'])){
            header("location:../modifierMarque.php?popUpText=6");
        }

        $idMarque=$_POST['idMarque'];
        $nomMarque=$_POST['nomMarque'];

        $marque = new Marque($bdd , $idMarque);
        if(!$marque->getExiste()){
            header("location:../modifierMarque.php?popUpText=17");
        }

        $destination = "../../../res/images/marque/"; 
        $allowTypes = array('jpg','png','jpeg','gif', 'heivc', 'webp'); 

        if(isset($_FILES['files'])){ 
            $produit_ajouter=false;
            $extension = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
                    
            if(in_array($extension, $allowTypes)){
                /* create new name file */
                $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241

                $basename = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg

                $source = $_FILES["files"]["tmp_name"];
                $path = $destination.$basename;

                /* move the file */
                if(move_uploaded_file( $source, $path )){
                    unlink($destination.$res['imageMarque']);

                    $query=$bdd->prepare('UPDATE marque SET imageMarque=?, nomMarque=? WHERE idMarque=?');
                    if($query->execute(array($basename, $nomMarque, $idMarque))){
                        echo("ok");
                        header("location:../modifierMarque.php?popUpText=12");
                    }
                    else{
                        echo("non");
                        header("location:../modifierMarque.php?popUpText=9");
                    }
                }
                else{
                    echo('Ajoue impossible.'); 
                    header("location:../modifierMarque.php?popUpText=13");
                }  
            } 
        }
        else{ 
            $query=$bdd->prepare('UPDATE marque SET nomMarque=? WHERE idMarque=?');
            if($query->execute(array($nomMarque, $idMarque))){
                echo("ok");
                header("location:../modifierMarque.php?popUpText=12");
            }
            else{
                echo("non");
                header("location:../modifierMarque.php?popUpText=9");
            }
        }
    }
    catch(Exception $e){
        $e->getMessage();
        header("location:../modifierMarque.php?popUpText=7");
    }

    ob_end_flush();

?>