<?php
include_once("ObjectModel.php");
class Collection extends ObjectModel
{
    private $id;
    private $nomCollection;
    private $descriptionCollection;
    private $idBoutique;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `collection` WHERE idCollection=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idCollection'];
                $this->nomCollection = $res['nomCollection'];
                $this->descriptionCollection = $res['descriptionCollection'];
                $this->idBoutique = $res['id_boutique'];

                $this->json_array = array(
                    'idCollection' => $this->id,
                    'nomCollection' => $this->nomCollection,
                    'descriptionCollection' => $this->descriptionCollection,
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
            $query = $this->objetPDO->prepare('DELETE FROM `collection` WHERE idCollection=?');
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNomCollection()
    {
        return $this->nomCollection;
    }
    public function getDescriptionCollection()
    {
        return $this->descriptionCollection;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
    public function update($nom, $desc)
    {
        $query = $this->objetPDO->prepare('UPDATE `collection` SET nomCollection=? , descriptionCollection=? WHERE idCollection=?');

        if ($query->execute(array($nom, $desc, $this->id))) {
            $this->nomCollection = $nom;
            $this->descriptionCollection = $desc;
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
