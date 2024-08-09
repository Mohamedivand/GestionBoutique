<?php
include_once("ObjectModel.php");
class SousTraitence extends ObjectModel
{
    private $id;
    private $reduction;
    private $date;
    private $nomBoutique;
    private $statut;
    private $contact;
    private $id_boutique;
    private $tableauProduit;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require_once("Produit.php");
            require_once("Contact.php");

            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM sousTraitence WHERE idSousTraitence=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idSousTraitence'];
                $this->reduction = $res['reduction'];
                $this->date = $res['date'];
                $this->nomBoutique = $res['nomBoutique'];
                $this->statut = $res['statut'];
                $this->contact = new Contact($this->objetPDO, $res['id_contact']);
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idSousTraitence" => $this->id,
                    "reduction" => $this->reduction,
                    "date" => $this->date,
                    "nomBoutique" => $this->nomBoutique,
                    "statut" => $this->statut,
                    "contactBoutique" => $this->contact->getJson_array(),
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
            $query = $this->objetPDO->prepare('SELECT * FROM sousTraitence WHERE idSousTraitence=?');
            $query->execute(array($this->id));
            if ($res = $query->fetch()) {
                $this->id = $res['idSousTraitence'];
                $this->reduction = $res['reduction'];
                $this->date = $res['date'];
                $this->nomBoutique = $res['nomBoutique'];
                $this->statut = $res['statut'];
                $this->contact = new Contact($this->objetPDO, $res['id_contact']);
                $this->id_boutique = $res['id_boutique'];

                $this->json_array = array(
                    "idSousTraitence" => $this->id,
                    "reduction" => $this->reduction,
                    "date" => $this->date,
                    "nomBoutique" => $this->nomBoutique,
                    "statut" => $this->statut,
                    "contactBoutique" => $this->contact->getJson_array(),
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
        return $this->id;
    }
    public function getReduction()
    {
        return $this->reduction;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getNomBoutique()
    {
        return $this->nomBoutique;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getContact()
    {
        return $this->contact;
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
            $query = $this->objetPDO->prepare("UPDATE sousTraitence SET statut=? WHERE idSousTraitence=?");
            return $query->execute([$val, $this->id]);
        } catch (Exception $e) {
            return false;
        }
    }

    private function chargerProduit()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM sousTraitenceProduit WHERE id_sousTraitence=?');
            $query->execute(array($this->id));
            $tableauTmp = array();
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauProduit[] = array(
                    "quantite" => $res['quantite'],
                    "prix" => $res['prix'],
                    "produit" => new Produit($this->objetPDO, $res['id_produit'])
                );
                $tableauTmp[] = array(
                    "quantite" => $res['quantite'],
                    "prix" => $res['prix'],
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
                    $query = $this->objetPDO->prepare("INSERT INTO sousTraitenceProduit (quantite, id_produit, id_sousTraitence) VALUES(?,?,?)");
                    $result = $query->execute([
                        $ligne['quantite'],
                        $ligne['idProduit'],
                        $this->id
                    ]);
                }
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function editeLigne($idProduit, $prix, $quantite)
    {
        try {
            $produit = new Produit($this->objetPDO, $idProduit);
            if (!$produit->getExiste()) {
                return false;
            }

            $query = $this->objetPDO->prepare("UPDATE sousTraitenceProduit SET prix=?, quantite=? WHERE id_sousTraitence=?");
            return $query->execute([
                $prix,
                $quantite,
                $this->id
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer()
    {
        try {
            $query = $this->objetPDO->prepare("DELETE FROM sousTraitence WHERE idSousTraitence=?");
            return $query->execute([$this->id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function valider()
    {
        try {
            if (is_null($this->tableauProduit)) {
                return false;
            }

            $result = false;

            foreach ($this->tableauProduit as $ligne) {
                if (!$result) {
                    $result = true;
                    $result = $ligne['produit']->setQuantite($ligne['produit']->getQuantite() + $ligne['quantite']);
                }
            }

            if ($result) {
                $this->setStatut(1);
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}
// ob_end_flush();
