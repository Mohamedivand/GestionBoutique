<?php
include_once("ObjectModel.php");
class Type extends ObjectModel
{
    private $id;
    private $nomType;
    private $descriptionType;
    private $idBoutique;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `type` WHERE idType=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idType'];
                $this->nomType = $res['nomType'];
                $this->descriptionType = $res['descriptionType'];
                $this->idBoutique = $res['id_boutique'];

                $this->json_array = array(
                    'idType' => $this->id,
                    'nomType' => $this->nomType,
                    'descriptionType' => $this->descriptionType,
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
            $query = $this->objetPDO->prepare('DELETE FROM `type` WHERE idType=?');
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNomType()
    {
        return $this->nomType;
    }
    public function getDescriptionType()
    {
        return $this->descriptionType;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
    public function update($nom, $desc)
    {
        $query = $this->objetPDO->prepare('UPDATE `type` SET nomType=? , descriptionType=? WHERE idType=?');

        if ($query->execute(array($nom, $desc, $this->id))) {
            $this->nomType = $nom;
            $this->descriptionType = $desc;
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
