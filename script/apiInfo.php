<?php
    $API_TOKEN="djessyaroma1234";
    function checkToken($token, $bdd){
        try{
            if(isset($_POST['token'])){
                if($_POST['token']==$token){
                    return true;
                }
                else{
                    $query=$bdd->prepare('SELECT * FROM `site` WHERE token=?');
                    $query->execute(array($_POST['token']));
                    if($query->fetch()){
                        return true;
                    }
                    else{
                        return false;
                    }
                }              
            } 
            else{
                return false;
            }
        }
        catch(Exception $e){
            return false;
        }
    }
?>