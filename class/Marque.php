<?php
include_once("ObjectModel.php");
class Marque extends ObjectModel
{
    private $id;
    private $nomMarque;
    private $imageMarque;
    private $idBoutique;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `marque` WHERE idMarque=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idMarque'];
                $this->nomMarque = $res['nomMarque'];
                $this->imageMarque = $res['imageMarque'];
                $this->idBoutique = $res['id_boutique'];

                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                // 3. append domain name
                $full_url .= $_SERVER["SERVER_NAME"];

                $imageTmp_path = $full_url . "/res/images/marque/" . $this->imageMarque;

                $this->json_array = array(
                    'idMarque' => $this->id,
                    'nomMarque' => $this->nomMarque,
                    'imageMarque' => $imageTmp_path,
                    'id_boutique' => $this->idBoutique
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function delete()
    {
        try {
            $query = $this->objetPDO->prepare('DELETE FROM marque WHERE idMarque=?');
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNomMarque()
    {
        return $this->nomMarque;
    }
    public function getImageMarque()
    {
        return $this->imageMarque;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
}

// require('../script/connexion_bd.php');
// $test= new Marque($bdd, 5);

// $test->showJson();
// ob_end_flush();
