<?php
include_once("ObjectModel.php");
class Vente extends ObjectModel
{
    private $id;
    private $reduction;
    private $montantPayer;
    private $dateVente;
    private $dateRemboursement;
    private $statut;
    private $idBoutique;
    private $tableauProduit;
    private $tableauPaiement;
    private $client;
    private $typeVente;
    private $total_prix_produit = 0;
    private $total_a_payer = 0;
    private $benefice;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require_once("User.php");
            require_once("Produit.php");
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `vente` WHERE idVente=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }

            if (is_array($res)) {
                $this->id = $res['idVente'];
                $this->reduction = (isset($res['reduction'])) ? $res['reduction'] : 0;
                $this->montantPayer = $res['montantPayer'];
                $this->total_prix_produit = $res['total_prix_produit'];
                $this->benefice = $res['benefice'];
                $this->total_a_payer = $this->total_prix_produit - $this->reduction;
                $this->dateVente = $res['dateVente'];
                $this->dateRemboursement = $res['dateRemboursement'];
                $this->statut = $res['statut'];
                $this->typeVente = $res['typeVente'];
                $this->idBoutique = $res['id_boutique'];
                $this->client = isset($res['id_user']) ? new User($this->objetPDO, $res['id_user']) : null;

                $this->json_array = array(
                    'idVente' => $this->id,
                    'reduction' => $this->reduction,
                    'montantPayer' => $this->montantPayer,
                    'dateVente' => $this->dateVente,
                    'dateRemboursement' => $this->dateRemboursement,
                    'statut' => $this->statut,
                    'typeVente' => $this->typeVente,
                    'client' => is_null($this->client) ? null : $this->client->getJson_array(),
                    'total_a_payer' => $this->total_a_payer,
                    "reste_a_payer" => ($this->total_a_payer > $this->montantPayer) ? $this->total_a_payer - $this->montantPayer : 0,
                    "benefice" => $this->benefice
                );

                $this->chargerProduit();

                // if($this->chargerProduit()){
                //     foreach($this->tableauProduit as $produit){
                //         // det pour detail et gro pour grossiste
                //         if($this->typeVente=='det'){
                //             $this->total_a_payer += $produit['produit']->getPrixVenteDetail() * $produit['quantite'] - $this->reduction;
                //             $this->benefice += $produit['produit']->getPrixVenteDetail() * $produit['quantite'] - $this->reduction - $produit['produit']->getPrixAchat();
                //         }
                //         else{
                //             $this->total_a_payer += $produit['produit']->getPrixVenteEngros() * $produit['quantite'] - $this->reduction;
                //             $this->benefice += $produit['produit']->getPrixVenteEngros() * $produit['quantite'] - $this->reduction - $produit['produit']->getPrixAchat();
                //         }
                //     }
                // }

                // $this->json_array = array_merge(
                //     $this->json_array ,
                //     array(
                //         'total_a_payer' => $this->total_a_payer,
                //         "reste_a_payer" => ($this->total_a_payer > $this->montantPayer) ? $this->total_a_payer - $this->montantPayer : 0,
                //         "benefice" => $this->benefice
                //     ) 
                // );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function reconstruct()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM `vente` WHERE idVente=?');
            $query->execute(array($this->id));
            if ($res = $query->fetch()) {
                $this->id = $res['idVente'];
                $this->reduction = (isset($res['reduction'])) ? $res['reduction'] : 0;
                $this->montantPayer = $res['montantPayer'];
                $this->total_prix_produit = $res['total_prix_produit'];
                $this->benefice = $res['benefice'];
                $this->total_a_payer = $this->total_prix_produit - $this->reduction;
                $this->dateVente = $res['dateVente'];
                $this->dateRemboursement = $res['dateRemboursement'];
                $this->statut = $res['statut'];
                $this->typeVente = $res['typeVente'];
                $this->idBoutique = $res['id_boutique'];
                $this->client = isset($res['id_user']) ? new User($this->objetPDO, $res['id_user']) : null;

                $this->json_array = array(
                    'idVente' => $this->id,
                    'reduction' => $this->reduction,
                    'montantPayer' => $this->montantPayer,
                    'dateVente' => $this->dateVente,
                    'dateRemboursement' => $this->dateRemboursement,
                    'statut' => $this->statut,
                    'typeVente' => $this->typeVente,
                    'client' => is_null($this->client) ? null : $this->client->getJson_array(),
                    'total_a_payer' => $this->total_a_payer,
                    "reste_a_payer" => ($this->total_a_payer > $this->montantPayer) ? $this->total_a_payer - $this->montantPayer : 0,
                    "benefice" => $this->benefice
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function valider()
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE vente SET statut = ? WHERE idVente=?");
            return $query->execute(array(1, $this->id));
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerPaimement()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM paiementDette WHERE id_vente=?');
            // echo(1);
            $query->execute(array($this->id));
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauPaiement[] = array(
                    "idPaiementDette" => $res['idPaiementDette'],
                    "montant" => $res['montant'],
                    "datePaiement" => $res['datePaiement'],
                );
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('historiquePaiement' =>  $this->tableauPaiement)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer()
    {
        try {
            $suppresion = true;
            if (!is_null($this->tableauProduit)) {
                foreach ($this->tableauProduit as $ligne) {
                    if (!$ligne['produit']->setQuantite($ligne['produit']->getQuantite() + $ligne['quantite'])) {
                        $suppresion = false;
                    }
                }
            }

            if ($suppresion) {
                $query = $this->objetPDO->prepare("DELETE FROM vente WHERE idVente=?");
                return $query->execute(array($this->id));
            } else {
                return false;
            }
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
                $benefice = 0;
                foreach ($array as $ligne) {
                    $produit_tmp = new Produit($this->objetPDO, $ligne['idProduit']);

                    if ($produit_tmp->getExiste()) {
                        if (isset($ligne['prixPersonnel']) && is_numeric($ligne['prixPersonnel'])) {
                            $prixTmp = $ligne['prixPersonnel'];
                        } else {
                            $prixTmp = $this->typeVente == 'det' ? $produit_tmp->getPrixVenteDetail() : $produit_tmp->getPrixVenteEngros();
                        }

                        $query = $this->objetPDO->prepare("INSERT INTO venteProduit (quantiteVenteProduit, prixVenteProduit, id_produit, id_vente) VALUES(?,?,?,?)");
                        $result = $query->execute(array($ligne['quantite'], $prixTmp, $produit_tmp->getId(), $this->id));

                        $query = $this->objetPDO->prepare("UPDATE produit SET quantiteProduit=? WHERE idProduit=?");
                        if ($result) {
                            $query->execute(
                                array(
                                    ($produit_tmp->getQuantite() - $ligne['quantite'] >= 0) ? $produit_tmp->getQuantite() - $ligne['quantite'] : 0,
                                    $produit_tmp->getId()
                                )
                            );

                            $total_prix_produit += $ligne['quantite'] * $prixTmp;
                            $benefice += $ligne['quantite'] * ($prixTmp - $produit_tmp->getPrixAchat());
                        }
                    }
                }

                $this->setBenefice($benefice);
                $this->setTotal_prix_produit($total_prix_produit);
            }

            return $result;
        } catch (Exception $e) {
            echo ($e->getMessage());
            return false;
        }
    }

    private function chargerProduit()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM venteProduit WHERE id_vente=?');
            $query->execute(array($this->id));
            $tableauTmp = array();
            $existe = false;
            while ($res = $query->fetch()) {
                $existe = true;
                $this->tableauProduit[] = array(
                    "quantite" => $res['quantiteVenteProduit'],
                    "prixVenteProduit" => $res['prixVenteProduit'],
                    "produit" => new Produit($this->objetPDO, $res['id_produit'])
                );
                $tableauTmp[] = array(
                    "quantite" => $res['quantiteVenteProduit'],
                    "prixVenteProduit" => $res['prixVenteProduit'],
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

    public function reduireDette($montant)
    {
        $montantFinal = ($montant + $this->montantPayer - $this->reduction < $this->total_a_payer) ? $montant + $this->montantPayer - $this->reduction : $this->total_a_payer;

        $query = $this->objetPDO->prepare('UPDATE vente SET montantPayer=? WHERE idVente=?');

        $res = $query->execute(array($montantFinal, $this->id));

        $query = $this->objetPDO->prepare('INSERT INTO paiementDette (montant, id_vente) VALUES (?, ?)');
        $query->execute(array($montant, $this->id));

        return $res;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getReduction()
    {
        return $this->reduction;
    }
    public function getMontantPayer()
    {
        return $this->montantPayer;
    }
    public function getDateVente()
    {
        return $this->dateVente;
    }
    public function getDateRemboursement()
    {
        return $this->dateRemboursement;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getIdBoutique()
    {
        return $this->idBoutique;
    }
    public function getClient()
    {
        return $this->client;
    }
    public function getTableauProduit()
    {
        return $this->tableauProduit;
    }
    public function getTableauPaiement()
    {
        return $this->tableauPaiement;
    }
    public function getTotal_a_payer()
    {
        return $this->total_a_payer;
    }
    public function getBenefice()
    {
        return $this->benefice;
    }
    public function getTypeVente()
    {
        return $this->typeVente;
    }

    public function setBenefice($val)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE vente SET benefice=? WHERE idVente=?");
            $res = $query->execute(array(
                $val + $this->benefice,
                $this->id
            ));

            $this->benefice += ($res) ? $val : 0;

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setTotal_prix_produit($val)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE vente SET total_prix_produit=? WHERE idVente=?");
            $res = $query->execute(array(
                $val + $this->total_prix_produit,
                $this->id
            ));

            $this->total_prix_produit += ($res) ? $val : 0;

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Vente($bdd, 83);

// $test->chargerPaimement();

// $test->showJson();
// ob_end_flush();
