<?php
include_once("ObjectModel.php");
class Proformat extends ObjectModel
{
    private $idProformat;
    private $reduction;
    private $total;
    private $dateProformat;
    private $typeVente;
    private $client;
    private $id_boutique;
    private $tableauProduit;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require_once("User.php");
            require_once("Produit.php");
            require_once("Vente.php");

            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM proformat WHERE idProformat=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->idProformat = $res['idProformat'];
                $this->reduction = $res['reduction'];
                $this->total = $res['total'];
                $this->dateProformat = $res['dateProformat'];
                $this->typeVente = $res['typeVente'];
                $this->client = new User($this->objetPDO, $res['id_user']);
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idProformat" => $this->idProformat,
                    "reduction" => $this->reduction,
                    "total" => $this->total,
                    "dateProformat" => $this->dateProformat,
                    "typeVente" => $this->typeVente,
                    "client" => $this->client->getJson_array(),
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
            $query = $this->objetPDO->prepare('SELECT * FROM proformat WHERE idProformat=?');
            $query->execute(array($this->idProformat));
            if ($res = $query->fetch()) {
                $this->idProformat = $res['idProformat'];
                $this->reduction = $res['reduction'];
                $this->total = $res['total'];
                $this->dateProformat = $res['dateProformat'];
                $this->typeVente = $res['typeVente'];
                $this->client = new User($this->objetPDO, $res['id_user']);
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idProformat" => $this->idProformat,
                    "reduction" => $this->reduction,
                    "total" => $this->total,
                    "dateProformat" => $this->dateProformat,
                    "typeVente" => $this->typeVente,
                    "client" => $this->client->getJson_array(),
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
        return $this->idProformat;
    }
    public function getReduction()
    {
        return $this->reduction;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getDateProformat()
    {
        return $this->dateProformat;
    }
    public function getTypeVente()
    {
        return $this->typeVente;
    }
    public function getClient()
    {
        return $this->client;
    }
    public function getId_boutique()
    {
        return $this->id_boutique;
    }
    public function getTableauProduit()
    {
        return $this->tableauProduit;
    }

    public function setTotal($val)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE proformat SET total=? WHERE idProformat=?");
            $res = $query->execute(array(
                $val + $this->total,
                $this->idProformat
            ));

            $this->total += ($res) ? $val : 0;

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    private function chargerProduit()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM proformatProduit WHERE id_proformat=?');
            $query->execute(array($this->idProformat));
            $tableauTmp = array();
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauProduit[] = array(
                    "quantite" => $res['quantiteProformatProduit'],
                    "prixProformatProduit" => $res['prixProformatProduit'],
                    "produit" => new Produit($this->objetPDO, $res['id_produit'])
                );
                $tableauTmp[] = array(
                    "quantite" => $res['quantiteProformatProduit'],
                    "prixProformatProduit" => $res['prixProformatProduit'],
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
                $total_prix_produit = 0;
                foreach ($array as $ligne) {
                    $produit_tmp = new Produit($this->objetPDO, $ligne['idProduit']);

                    if ($produit_tmp->getExiste()) {
                        if (isset($ligne['prixPersonnel']) && is_numeric($ligne['prixPersonnel'])) {
                            $prixTmp = $ligne['prixPersonnel'];
                        } else {
                            $prixTmp = $this->typeVente == 'det' ? $produit_tmp->getPrixVenteDetail() : $produit_tmp->getPrixVenteEngros();
                        }

                        $query = $this->objetPDO->prepare("INSERT INTO proformatProduit (quantiteProformatProduit, prixProformatProduit, id_produit, id_proformat) VALUES(?,?,?,?)");
                        $result = $query->execute(array($ligne['quantite'], $prixTmp, $produit_tmp->getId(), $this->idProformat));
                        $total_prix_produit += $ligne['quantite'] * $prixTmp;
                    }
                }
                $this->setTotal($total_prix_produit);
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function converteToVente()
    {
        try {
            $result = false;
            $query = $this->objetPDO->prepare("INSERT INTO vente (reduction, montantPayer, typeVente, id_boutique, id_user) VALUES (?,?,?,?,?)");
            if (
                $query->execute(array(
                    $this->reduction,
                    $this->total - $this->reduction,
                    $this->typeVente,
                    $this->id_boutique,
                    $this->client->getId()
                ))
            ) {
                $lastId = $this->objetPDO->lastInsertId();

                $vente = new Vente($this->objetPDO, $lastId);

                if (!is_null($this->tableauProduit)) {
                    foreach ($this->tableauProduit as $ligne) {
                        $listeProduit[] = array(
                            "quantite" => $ligne["quantite"],
                            "idProduit" => $ligne["produit"]->getId()
                        );
                    }

                    $result = $vente->ajouterProduit($listeProduit);
                }
            }

            $this->supprimer();

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer()
    {
        try {
            $query = $this->objetPDO->prepare("DELETE FROM proformat WHERE idProformat=?");
            return $query->execute(array($this->getId()));
        } catch (Exception $e) {
            return false;
        }
    }
}
// ob_end_flush();
