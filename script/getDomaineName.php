<?php
    function getDomaine(){
        try{
            // 1. write the http protocol
            $full_url = "http://";

            // 2. check if your server use HTTPS
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                $full_url = "https://";
            }

            // 3. append domain name
            $full_url .= $_SERVER["SERVER_NAME"];
            return $full_url;
        }
        catch(Exception $e){
            return false;
        }
    }
?>