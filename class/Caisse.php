<?php
include_once("ObjectModel.php");
class Caisse extends ObjectModel
{
    private $id;
    private $solde;
    private $id_boutique;
    private $tableauTransaction;

    public function __construct($bdd, $id, $id_boutique = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!is_null($id_boutique)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `caisse` WHERE id_boutique=?');
                $query->execute(array($id_boutique));
            } else {
                $query = $this->objetPDO->prepare('SELECT * FROM `caisse` WHERE idCaisse=?');
                $query->execute(array($id));
            }
            if ($res = $query->fetch()) {
                $this->id = $res['idCaisse'];
                $this->solde = $res['solde'];
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    'idCaisse' => $this->id,
                    'solde' => $this->solde,
                    'id_boutique' => $this->id_boutique
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getSolde()
    {
        return $this->solde;
    }
    public function getId_boutique()
    {
        return $this->id_boutique;
    }
    public function getTableauTransaction()
    {
        return $this->tableauTransaction;
    }

    public function setSolde($val)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE caisse SET solde=? WHERE idCaisse=?");
            if ($query->execute(array($val, $this->id))) {
                $this->solde = $val;
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function depot($montant, $date, $nom, $num)
    {
        try {
            $query = $this->objetPDO->prepare('INSERT INTO `transaction` (montant , `type`, dateTransaction, nomEmployer, numEmployer, id_caisse) VALUES(?,?,?,?,?,?)');

            if ($query->execute(
                array(
                    $montant,
                    1,
                    $date,
                    $nom,
                    $num,
                    $this->id
                )
            )) {
                return $this->setSolde($this->solde + $montant);
            } else {
                return false;
            }
        } catch (Exception $e) {
            // echo($e->getMessage());
            return false;
        }
    }

    public function retrait($montant, $motif, $date, $nom, $num)
    {
        try {
            $query = $this->objetPDO->prepare('INSERT INTO `transaction` (montant , `type`, motif, dateTransaction, nomEmployer, numEmployer, id_caisse) VALUES(?,?,?,?,?,?,?)');

            if ($query->execute(
                array(
                    $montant,
                    2,
                    $motif,
                    $date,
                    $nom,
                    $num,
                    $this->id
                )
            )) {
                return $this->setSolde($this->solde - $montant);
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimerTransaction($id)
    {
        try {
            $result = false;

            $query = $this->objetPDO->prepare("SELECT * FROM `transaction` WHERE idTransaction=? AND id_caisse=? LIMIT 1");
            $query->execute(array($id, $this->id));
            if ($res = $query->fetch()) {
                $montant = $res['montant'];
                $type = $res['type'];
                if ($type == 1) {
                    if (!$this->setSolde($this->solde - $montant)) {
                        return false;
                    }
                } else {
                    if (!$this->setSolde($this->solde + $montant)) {
                        return false;
                    }
                }
                $query = $this->objetPDO->prepare("DELETE FROM `transaction` WHERE idTransaction=? ");

                return $query->execute(array($id));
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerTransactions()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM `transaction` WHERE id_caisse=?');
            $query->execute(array($this->id));
            $existe = false;

            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauTransaction[] = array(
                    "idTransaction" => $res['idTransaction'],
                    "montant" => $res['montant'],
                    "type" => $res['type'],
                    "motif" => $res['motif'],
                    "dateTransaction" => $res['dateTransaction'],
                    "nomEmployer" => $res['nomEmployer'],
                    "numEmployer" => $res['numEmployer']
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
