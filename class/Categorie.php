<?php
include_once("ObjectModel.php");
class Categorie extends ObjectModel
{
    private $id;
    private $nomCategorie;
    private $descriptionCategorie;
    private $idBoutique;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `categorie` WHERE idCategorie=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idCategorie'];
                $this->nomCategorie = $res['nomCategorie'];
                $this->descriptionCategorie = $res['descriptionCategorie'];
                $this->idBoutique = $res['id_boutique'];

                $this->json_array = array(
                    'idCategorie' => $this->id,
                    'nomCategorie' => $this->nomCategorie,
                    'descriptionCategorie' => $this->descriptionCategorie,
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
            $query = $this->objetPDO->prepare('DELETE FROM categorie WHERE idCategorie=?');
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNomCategorie()
    {
        return $this->nomCategorie;
    }
    public function getDescriptionCategorie()
    {
        return $this->descriptionCategorie;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
    public function update($nom, $desc)
    {
        $query = $this->objetPDO->prepare('UPDATE `categorie` SET nomCategorie=? , descriptionCategorie=? WHERE idCategorie=?');

        if ($query->execute(array($nom, $desc, $this->id))) {
            $this->nomCategorie = $nom;
            $this->descriptionCategorie = $desc;
            return true;
        } else {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
