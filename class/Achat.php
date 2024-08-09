<?php
include_once("ObjectModel.php");
class Achat extends ObjectModel
{
    private $idAchat;
    private $dateAchat;
    private $statut;
    private $id_boutique;
    private $tableauProduit;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require_once("Produit.php");

            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM achat WHERE idAchat=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }

            if (is_array($res)) {
                $this->idAchat = $res['idAchat'];
                $this->dateAchat = $res['dateAchat'];
                $this->statut = $res['statut'];

                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idAchat" => $this->idAchat,
                    "dateAchat" => $this->dateAchat,
                    "statut" => $this->statut,
                    "id_boutique" => $this->id_boutique
                );

                $this->chargerProduit();

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function reconstruct()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM achat WHERE idAchat=?');
            $query->execute(array($this->idAchat));
            if ($res = $query->fetch()) {
                $this->idAchat = $res['idAchat'];
                $this->dateAchat = $res['dateAchat'];
                $this->statut = $res['statut'];

                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idAchat" => $this->idAchat,
                    "dateAchat" => $this->dateAchat,
                    "statut" => $this->statut,
                    "id_boutique" => $this->id_boutique
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function getId()
    {
        return $this->idAchat;
    }
    public function getDate()
    {
        return $this->dateAchat;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getId_boutique()
    {
        return $this->id_boutique;
    }
    public function getTableauProduit()
    {
        return $this->tableauProduit;
    }

    public function setStatut($val)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE achat SET statut=? WHERE idAchat=?");
            return $query->execute(
                [
                    $val,
                    $this->idAchat
                ]
            );
        } catch (Exception $e) {
            return false;
        }
    }

    private function chargerProduit()
    {
        try {
            $this->tableauProduit = null;

            $query = $this->objetPDO->prepare('SELECT * FROM achatProduit WHERE id_achat=?');
            $query->execute(array($this->idAchat));
            $tableauTmp = array();
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauProduit[] = array(
                    "quantiteDemander" => $res['quantiteDemander'],
                    "quantiteRecu" => $res['quantiteRecu'],
                    "produit" => new Produit($this->objetPDO, $res['id_produit'])
                );
                $tableauTmp[] = array(
                    "quantiteDemander" => $res['quantiteDemander'],
                    "quantiteRecu" => $res['quantiteRecu'],
                    "produit" => (new Produit($this->objetPDO, $res['id_produit']))->getJson_array()
                );
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('lesproduit' =>  $tableauTmp)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function ajouterProduit($array)
    {
        try {
            $result = false;
            if (is_array($array)) {
                foreach ($array as $ligne) {
                    $produit_tmp = new Produit($this->objetPDO, $ligne['idProduit']);
                    if ($produit_tmp->getExiste()) {
                        $query = $this->objetPDO->prepare("INSERT INTO achatProduit (quantiteDemander, quantiteRecu, id_produit, id_achat) VALUES(?,?,?,?)");
                        $result = $query->execute(array($ligne['quantite'], $ligne['quantite'], $produit_tmp->getId(), $this->idAchat));
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setQuantiteProduitRecu($val, $idProduit)
    {
        try {
            $query = $this->objetPDO->prepare("SELECT * FROM achatProduit WHERE id_produit=? AND id_achat=?");
            $query->execute([
                $idProduit,
                $this->idAchat
            ]);

            if (!$query->fetch()) {
                return false;
            }

            $query = $this->objetPDO->prepare("UPDATE achatProduit SET quantiteRecu=? WHERE id_produit=? AND id_achat=?");
            return $query->execute([
                $val,
                $idProduit,
                $this->idAchat
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function valider()
    {
        try {
            $result = false;

            if (!is_null($this->tableauProduit)) {
                foreach ($this->tableauProduit as $ligne) {
                    $tmp = $ligne["produit"];

                    if (
                        $tmp->ajouterStockBoutique($ligne["quantiteRecu"])
                    ) {
                        $result = true;
                    }
                }

                return ($result) ? $this->setStatut(1) : false;
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer()
    {
        try {
            $query = $this->objetPDO->prepare("DELETE FROM achat WHERE idAchat=?");
            return $query->execute(array($this->getId()));
        } catch (Exception $e) {
            return false;
        }
    }
}
