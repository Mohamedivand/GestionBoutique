<?php
include_once("ObjectModel.php");
class Commande extends ObjectModel
{
    private $id;
    private $statut;
    private $dateCommande;
    private $typeCommande;
    private $contact;
    private $utilisateur_action;
    private $idBoutique;
    private $tableauProduit;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require_once("Contact.php");
            require_once("User.php");
            require_once("Produit.php");
            require_once("Role.php");
            require_once("Vente.php");

            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `commande` WHERE idCommande=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idCommande'];
                $this->statut = $res['statutCommande'];
                $this->dateCommande = $res['dateCommande'];
                $this->typeCommande = $res['typeCommande'];
                $this->contact = new Contact($this->objetPDO, $res['id_contact']);
                $this->utilisateur_action = (isset($res['id_user'])) ? new User($this->objetPDO, $res['id_user']) : null;
                $this->idBoutique =  $res['id_boutique'];

                $this->json_array = array(
                    'idCommande' => $this->id,
                    'statutCommande' => $this->statut,
                    'typeCommande' => $this->typeCommande,
                    'dateCommande' => $this->dateCommande,
                    'contact' => $this->contact->getJson_array(),
                    'utilisateur' => (is_null($this->utilisateur_action)) ? null : $this->utilisateur_action->getJson_array(),
                );

                $this->chargerProduit();

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
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
                        $query = $this->objetPDO->prepare("INSERT INTO commandeProduit (quantiteCommandeProduit, id_produit, id_commande) VALUES(?,?,?)");
                        $result = $query->execute(array($ligne['quantite'], $produit_tmp->getId(), $this->id));
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setQuantiteRetournerProduit($val, $idProduit)
    {
        try {
            $result = false;

            $produit = new Produit($this->objetPDO, $idProduit);

            if (!$produit->getExiste()) {
                return false;
            }

            $query = $this->objetPDO->prepare("UPDATE commandeProduit SET quantiteRetourner=? WHERE id_produit=? AND id_commande=?");

            return $query->execute([
                $val,
                $produit->getId(),
                $this->id
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function converteToVente()
    {
        try {
            $result = true;

            if (!$this->valider()) {
                return false;
            }

            $montant = 0;

            if (is_null($this->tableauProduit)) {
                return false;
            }

            foreach ($this->tableauProduit as $ligne) {
                $produit_tmp = new Produit($this->objetPDO, $ligne["produit"]['idProduit']);

                $quantite = ($ligne["quantite"] - $ligne["quantiteRetourner"]);

                $montant += $quantite * (($this->typeCommande == "det") ? $produit_tmp->getPrixVenteDetail() : $produit_tmp->getPrixVenteEngros());

                $listeProduit[] = array(
                    "quantite" => $quantite,
                    "idProduit" => $produit_tmp->getId()
                );
            }

            $query = $this->objetPDO->prepare("SELECT * FROM `role` WHERE nomRole=?");

            $query->execute(array("client"));

            if (!($res = $query->fetch())) {
                return false;
            }

            $query = $this->objetPDO->prepare("INSERT INTO user (nomUser, prenomUser, id_contact, id_role) VALUES (?,?,?,?)");

            if (!$query->execute([
                $this->contact->getTel(),
                $this->contact->getTel(),
                $this->contact->getId(),
                $res['idRole']
            ])) {
                return false;
            }

            $idClient = $this->objetPDO->lastInsertId();

            $query2 = $this->objetPDO->prepare("INSERT INTO vente (montantPayer, typeVente, id_user, id_boutique) VALUES (?,?,?,?)");

            if (
                !$query2->execute([
                    $montant,
                    $this->typeCommande,
                    $idClient,
                    $this->idBoutique
                ])
            ) {
                $result = false;
            }

            $lastId = $this->objetPDO->lastInsertId();
            $vente = new Vente($this->objetPDO, $lastId);

            $result = $vente->ajouterProduit($listeProduit);

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function valider()
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE commande SET statutCommande = ? WHERE idCommande=?");
            return $query->execute(array(1, $this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer()
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE commande SET statutCommande = ? WHERE idCommande=?");
            return $query->execute(array(2, $this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    private function chargerProduit()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM commandeProduit WHERE id_commande=?');
            $query->execute(array($this->id));
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauProduit[] = array(
                    "quantite" => $res['quantiteCommandeProduit'],
                    "quantiteRetourner" => $res['quantiteRetourner'],
                    "produit" => (new Produit($this->objetPDO, $res['id_produit']))->getJson_array()
                );
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('lesproduit' =>  $this->tableauProduit)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getDateCommande()
    {
        return $this->dateCommande;
    }
    public function getContact()
    {
        return $this->contact;
    }
    public function getTypeCommande()
    {
        return $this->typeCommande;
    }
    public function getUtilisateur_action()
    {
        return $this->utilisateur_action;
    }
    public function getTableauProduit()
    {
        return $this->tableauProduit;
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
