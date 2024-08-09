<?php
    try{
        require("../script/connexion_bd.php");
        require("../class/User.php");
        $query = $bdd->query("SELECT * FROM user WHERE id_role=4");
        while($res = $query->fetch()){
            $user = new User($bdd, $res['idUser']);
            echo $user->setTel("--") . "<br>";
        }
    }
    catch(Exception $e){
        echo($e->getMessage());
    }
?>