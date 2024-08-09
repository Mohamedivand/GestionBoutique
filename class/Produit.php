<?php
include_once("ObjectModel.php");
class Produit extends ObjectModel
{
    private $id;
    private $nomProduit;
    private $prixAchat;
    private $prixVenteEngros;
    private $prixVenteDetail;
    private $imageProduit;
    private $dateAjout;
    private $quantite;
    private $quantiteEntrepot;
    private $description;
    private $codeBar;
    private $dateModification;
    private $statut;
    private $categorie;
    private $type;
    private $marque;
    private $collection;
    private $idBoutique;
    private $tableauAutre;
    private $fournisseur;
    private $tableauImage;

    public function __construct($bdd, $id, $res = null)
    {
        require_once("Categorie.php");
        require_once("Type.php");
        require_once("Marque.php");
        require_once("Collection.php");
        require_once("User.php");
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `produit` WHERE idProduit=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }
            if (is_array($res)) {
                $this->id = $res['idProduit'];
                $this->nomProduit = $res['nomProduit'];
                $this->prixAchat = $res['prixAchat'];
                $this->prixVenteDetail = $res['prixVenteDetail'];
                $this->prixVenteEngros = $res['prixVenteEngros'];
                $this->imageProduit = $res['imageProduit'];
                $this->dateAjout = $res['dateAjoutProduit'];
                $this->quantite = $res['quantiteProduit'];
                $this->quantiteEntrepot = $res['quantiteEntrepot'];
                $this->description = $res['descriptionProduit'];
                $this->codeBar = isset($res['codeBar']) ? $res['codeBar'] : null;
                $this->dateModification = $res['dateModificationProduit'];
                $this->statut = $res['statutProduit'];
                $this->categorie = new Categorie($this->objetPDO, $res['id_categorie']);
                $this->type = new Type($this->objetPDO, $res['id_type']);
                $this->marque = new Marque($this->objetPDO, $res['id_marque']);
                $this->collection = new Collection($this->objetPDO, $res['id_collection']);
                $this->fournisseur = (isset($res['id_user'])) ? new User($this->objetPDO, $res['id_user']) : null;
                $this->idBoutique = $res['id_boutique'];


                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                // 3. append domain name
                $full_url .= $_SERVER["SERVER_NAME"];

                $imageTmp_path = isset($this->imageProduit) ? $full_url . "/res/images/produit/" . $this->imageProduit : null;
                $this->json_array = array(
                    'idProduit' => $this->id,
                    'nomProduit' => $this->nomProduit,
                    'prixAchat' => $this->prixAchat,
                    'prixVenteDetail' => $this->prixVenteDetail,
                    'prixVenteEngros' => $this->prixVenteEngros,
                    'imageProduit' => $imageTmp_path,
                    'dateAjoutProduit' => $this->dateAjout,
                    'quantiteProduit' => $this->quantite,
                    'quantiteEntrepot' => $this->quantiteEntrepot,
                    'descriptionProduit' => $this->description,
                    'codeBar' => $this->codeBar,
                    'dateModificationProduit' => $this->dateModification,
                    'statutProduit' => $this->statut,
                    'categorie' => $this->categorie->getJson_array(),
                    'type' => $this->type->getJson_array(),
                    'marque' => $this->marque->getJson_array(),
                    'collection' => $this->collection->getJson_array(),
                    'fournisseur' => (is_null($this->fournisseur)) ? null : $this->fournisseur->getJson_array(),
                );

                $query = $this->objetPDO->prepare('SELECT * FROM `imageProduit` WHERE id_produit=?');
                $query->execute(array($this->id));

                $existe = false;
                while ($res = $query->fetch()) {
                    $existe = true;
                    $imageTmp_path = $full_url . "/res/images/produit/" . $res['nomImageProduit'];
                    $this->tableauImage[] = array(
                        "idImageProduit" => $res['idImageProduit'],
                        "nomImageProduit" => $imageTmp_path
                    );
                }
                if ($existe) {
                    $this->json_array = array_merge(
                        $this->json_array,
                        array('sesImages' =>  $this->tableauImage)
                    );
                }

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    private function reconstruct()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM `produit` WHERE idProduit=?');
            $query->execute(array($this->id));
            if ($res = $query->fetch()) {
                $this->id = $res['idProduit'];
                $this->nomProduit = $res['nomProduit'];
                $this->prixAchat = $res['prixAchat'];
                $this->prixVenteDetail = $res['prixVenteDetail'];
                $this->prixVenteEngros = $res['prixVenteEngros'];
                $this->imageProduit = $res['imageProduit'];
                $this->dateAjout = $res['dateAjoutProduit'];
                $this->quantite = $res['quantiteProduit'];
                $this->quantiteEntrepot = $res['quantiteEntrepot'];
                $this->description = $res['descriptionProduit'];
                $this->codeBar = isset($res['codeBar']) ? $res['codeBar'] : null;
                $this->dateModification = $res['dateModificationProduit'];
                $this->statut = $res['statutProduit'];
                $this->categorie = new Categorie($this->objetPDO, $res['id_categorie']);
                $this->type = new Type($this->objetPDO, $res['id_type']);
                $this->marque = new Marque($this->objetPDO, $res['id_marque']);
                $this->collection = new Collection($this->objetPDO, $res['id_collection']);
                $this->fournisseur = isset($res['id_user']) ? new User($this->objetPDO, $res['id_user']) : null;
                $this->idBoutique = $res['id_boutique'];



                // 1. write the http protocol
                $full_url = "http://";

                // 2. check if your server use HTTPS
                if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                    $full_url = "https://";
                }

                // 3. append domain name
                $full_url .= $_SERVER["SERVER_NAME"];

                $imageTmp_path = $full_url . "/res/images/produit/" . $this->imageProduit;

                $this->json_array = array(
                    'idProduit' => $this->id,
                    'nomProduit' => $this->nomProduit,
                    'prixAchat' => $this->prixAchat,
                    'prixVenteDetail' => $this->prixVenteDetail,
                    'prixVenteEngros' => $this->prixVenteEngros,
                    'imageProduit' => $imageTmp_path,
                    'dateAjoutProduit' => $this->dateAjout,
                    'quantiteProduit' => $this->quantite,
                    'quantiteEntrepot' => $this->quantiteEntrepot,
                    'descriptionProduit' => $this->description,
                    'codeBar' => $this->codeBar,
                    'dateModificationProduit' => $this->dateModification,
                    'statutProduit' => $this->statut,
                    'categorie' => $this->categorie->getJson_array(),
                    'type' => $this->type->getJson_array(),
                    'marque' => $this->marque->getJson_array(),
                    'collection' => $this->collection->getJson_array(),
                    'fournisseur' => (is_null($this->fournisseur) ? null : $this->fournisseur->getJson_array()),
                );

                $query = $this->objetPDO->prepare('SELECT * FROM `imageProduit` WHERE id_produit=?');
                $query->execute(array($this->id));

                $existe = false;
                while ($res = $query->fetch()) {
                    $existe = true;
                    $imageTmp_path = $full_url . "/res/images/produit/" . $res['nomImageProduit'];
                    $this->tableauImage[] = array(
                        "idImageProduit" => $res['idImageProduit'],
                        "nomImageProduit" => $imageTmp_path
                    );
                }
                if ($existe) {
                    $this->json_array = array_merge(
                        $this->json_array,
                        array('sesImages' =>  $this->tableauImage)
                    );
                }

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
    public function getNomProduit()
    {
        return $this->nomProduit;
    }
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }
    public function getPrixVenteDetail()
    {
        return $this->prixVenteDetail;
    }
    public function getPrixVenteEngros()
    {
        return $this->prixVenteEngros;
    }

    public function getImageProduit()
    {
        return $this->imageProduit;
    }
    public function getDateAjout()
    {
        return $this->dateAjout;
    }
    public function getQuantite()
    {
        return $this->quantite;
    }
    public function getQuantiteEntrepot()
    {
        return $this->quantiteEntrepot;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCodeBar()
    {
        return $this->codeBar;
    }
    public function getDateModification()
    {
        return $this->dateModification;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getCategorie()
    {
        return $this->categorie;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getMarque()
    {
        return $this->marque;
    }
    public function getCollection()
    {
        return $this->collection;
    }
    public function getFournisseur()
    {
        return $this->fournisseur;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
    public function getTableauImage()
    {
        return $this->tableauImage;
    }
    public function update(
        $nom,
        $prixAchat,
        $prixVenteDetail,
        $prixVenteEngros,
        $imageProduit,
        $quantite,
        $quantiteEntrepot,
        $desc,
        $id_categorie,
        $id_type,
        $id_marque,
        $id_collection,
        $id_user,
        $codeBar,
    ) {
        $query = $this->objetPDO->prepare('UPDATE `produit` SET 
                nomProduit=? , 
                prixAchat=? , 
                prixVenteDetail=? , 
                prixVenteEngros=? , 
                imageProduit=? , 
                quantiteProduit=? , 
                quantiteEntrepot=? , 
                descriptionProduit=? , 
                codeBar=? , 
                id_categorie=? , 
                id_type=? , 
                id_marque=? , 
                id_collection=? , 
                id_user=? 
            WHERE idProduit=?');

        if ($query->execute(
            array(
                $nom,
                $prixAchat,
                $prixVenteDetail,
                $prixVenteEngros,
                $imageProduit,
                $quantite,
                $quantiteEntrepot,
                $desc,
                $codeBar,
                $id_categorie,
                $id_type,
                $id_marque,
                $id_collection,
                $id_user,
                $this->id
            )
        )) {
            $this->reconstruct();
            return true;
        } else {
            return false;
        }
    }

    public function setQuantite($val)
    {
        try {
            $res = true;
            if (!is_numeric($val)) {
                $res = false;
            }

            $query = $this->objetPDO->prepare("UPDATE produit SET quantiteProduit=? WHERE idProduit=?");
            if ($res = $query->execute(array($val, $this->id))) {
                $this->quantite = $val;
            };

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setQuantiteEntrepot($val)
    {
        try {
            $res = true;
            if (!is_numeric($val)) {
                $res = false;
            }

            $query = $this->objetPDO->prepare("UPDATE produit SET quantiteEntrepot=? WHERE idProduit=?");
            if ($res = $query->execute(array($val, $this->id))) {
                $this->quantiteEntrepot = $val;
            };

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public function ajouterStockBoutique($val)
    {
        try {
            if ($this->quantiteEntrepot < $val) {
                return false;
            }

            $res = true;

            $res = $this->setQuantite($val + $this->quantite);

            if ($res) {
                $res = $this->setQuantiteEntrepot($this->quantiteEntrepot - $val);
            }

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public function reduireStockEntrepot($val)
    {
        try {
            return $this->setQuantiteEntrepot($this->quantiteEntrepot - $val);
        } catch (Exception $e) {
            return false;
        }
    }

    public function ajouterStockEntrepot($val)
    {
        try {
            return $this->setQuantiteEntrepot($this->quantiteEntrepot + $val);
        } catch (Exception $e) {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Produit($bdd, 3);

// $test->showJson();
// ob_end_flush();
