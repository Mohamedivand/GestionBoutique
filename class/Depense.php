<?php
include_once("ObjectModel.php");
class Depense extends ObjectModel
{
    private $id;
    private $detail;
    private $montant;
    private $provientBenefice;
    private $dateDepense;
    private $idBoutique;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `depense` WHERE idDepense=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idDepense'];
                $this->detail = $res['detail'];
                $this->montant = $res['montant'];
                $this->provientBenefice = $res['provientBenefice'];
                $this->dateDepense = $res['dateDepense'];
                $this->idBoutique = $res['id_boutique'];

                $this->json_array = array(
                    'idDepense' => $this->id,
                    'detail' => $this->detail,
                    'montant' => $this->montant,
                    'provientBenefice' => $this->provientBenefice,
                    'dateDepense' => $this->dateDepense,
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
            $query = $this->objetPDO->prepare('DELETE FROM `depense` WHERE idDepense=?');
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getDetail()
    {
        return $this->detail;
    }
    public function getMontant()
    {
        return $this->montant;
    }
    public function getProvientBenefice()
    {
        return $this->provientBenefice;
    }
    public function getDateDepense()
    {
        return $this->dateDepense;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
