<?php
include_once("ObjectModel.php");
class CarteBancaire extends ObjectModel
{
    private $id;
    private $numeroCarte;
    private $solde;
    private $nomBanque;
    private $id_boutique;
    private $tableauTransaction;

    public function __construct($bdd, $id, $numeroCarte = null, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                if (!is_null($numeroCarte)) {
                    $query = $this->objetPDO->prepare('SELECT * FROM `carteBancaire` WHERE numeroCarte=?');
                    $query->execute(array($numeroCarte));
                } else {
                    $query = $this->objetPDO->prepare('SELECT * FROM `carteBancaire` WHERE idCarteBancaire=?');
                    $query->execute(array($id));
                }
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idCarteBancaire'];
                $this->numeroCarte = $res['numeroCarte'];
                $this->solde = $res['solde'];
                $this->nomBanque = isset($res['nomBanque']) ? $res['nomBanque'] : null;
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    'idCarteBancaire' => $this->id,
                    'numeroCarte' => $this->numeroCarte,
                    'nomBanque' => $this->nomBanque,
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
    public function getNumeroCarte()
    {
        return $this->numeroCarte;
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
            $query = $this->objetPDO->prepare("UPDATE carteBancaire SET solde=? WHERE idCarteBancaire=?");
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
            $query = $this->objetPDO->prepare('INSERT INTO `transaction` (montant , `type`, dateTransaction, nomEmployer, numEmployer, id_carteBancaire) VALUES(?,?,?,?,?,?)');

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
            $query = $this->objetPDO->prepare('INSERT INTO `transaction` (montant , `type`, motif, dateTransaction, nomEmployer, numEmployer, id_carteBancaire) VALUES(?,?,?,?,?,?,?)');

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

    public function chargerTransactions()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM `transaction` WHERE id_carteBancaire=?');
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

            $this->json_array = array_merge(
                $this->json_array,
                array('sesTransactions' =>  $this->tableauTransaction)
            );

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($numero, $solde)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE carteBancaire SET numeroCarte=?, solde=? WHERE idCarteBancaire=? ");
            return $query->execute(array($numero, $solde, $this->id));
        } catch (Exception $e) {
            return false;
        }
    }


    public function deleteCart()
    {
        try {
            $query = $this->objetPDO->prepare("DELETE FROM carteBancaire WHERE idCarteBancaire=? ");
            return $query->execute(array($this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimerTransaction($idTransaction)
    {
        try {
            $query = $this->objetPDO->prepare("SELECT * FROM `transaction` WHERE idTransaction=? AND id_carteBancaire=? LIMIT 1");
            $query->execute(array($idTransaction, $this->id));

            if (!($res = $query->fetch())) {
                return false;
            }

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

            $query = $this->objetPDO->prepare("DELETE FROM `transaction` WHERE idTransaction=?");
            return $query->execute(array($idTransaction));
        } catch (Exception $e) {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
