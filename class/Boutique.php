<?php
include_once("ObjectModel.php");
class Boutique extends ObjectModel
{
    private $id;
    private $nomBoutique;
    private $debutAbonnement;
    private $finAbonnement;
    private $date_ajoue_boutique;
    private $dateModificationBoutique;
    private $statutBoutique;
    private $caisseExiste;
    private $imageBanderole;
    private $imageTampon;
    private $textFooterPDF;
    private $contact;
    private $caisse;
    private $proprietaire;
    private $isDeprecated;
    private $tableauProduit;
    private $tableauFournisseur;
    private $tableauCommande;
    private $tableauVente;
    private $tableauVenteJour;
    private $tableauProformat;
    private $tableauSousTraitence;
    private $tableauAchat;
    private $tableauDepense;
    private $tableauCarteBancaire;

    public function __construct($bdd, $id, $token = false, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            require("Contact.php");
            require("User.php");
            require("Caisse.php");

            if ($token) {
                $query = $this->objetPDO->prepare('SELECT id_boutique FROM `site` WHERE token=?');
                $query->execute(array($token));

                if ($res = $query->fetch()) {
                    $query = $this->objetPDO->prepare('SELECT * FROM `boutique` WHERE idBoutique=?');
                    $query->execute(array($res['id_boutique']));
                    if ($res = $query->fetch()) {
                        $this->id = $res['idBoutique'];
                        $this->nomBoutique = $res['nomBoutique'];
                        $this->debutAbonnement = $res['debutAbonnement'];
                        $this->finAbonnement = $res['finAbonnement'];
                        $this->date_ajoue_boutique = $res['date_ajoue_boutique'];
                        $this->dateModificationBoutique = $res['dateModificationBoutique'];
                        $this->imageBanderole = $res['imageBanderole'];
                        $this->imageTampon = $res['imageTampon'];
                        $this->textFooterPDF = $res['textFooterPDF'];
                        $this->statutBoutique = $res['statutBoutique'];
                        $this->caisseExiste = $res['caisseExiste'];
                        $this->isDeprecated = $res['isDeprecated'];
                        $this->contact = new Contact($this->objetPDO, $res['id_contact_boutique']);
                        $this->proprietaire = new User($this->objetPDO, $res['id_user']);

                        $this->chercherCaisse();

                        $this->json_array = array(
                            'idBoutique' => $this->id,
                            'nomBoutique' => $this->nomBoutique,
                            'debutAbonnement' => $this->debutAbonnement,
                            'finAbonnement' => $this->finAbonnement,
                            'date_ajoue_boutique' => $this->date_ajoue_boutique,
                            'dateModificationBoutique' => $this->dateModificationBoutique,
                            'statutBoutique' => $this->statutBoutique,
                            'imageBanderole' => $this->imageBanderole,
                            'imageTampon' => $this->imageTampon,
                            'textFooterPDF' => $this->textFooterPDF,
                            'caisseExiste' => $this->caisseExiste,
                            'isDeprecated' => $this->isDeprecated,
                            'contact' => $this->contact->getJson_array(),
                            'proprietaire' => $this->proprietaire->getJson_array(),
                            "idCaisse" => $this->caisse->getId()
                        );

                        $this->existe = true;
                    }
                }
            } else {
                if (!isset($res)) {
                    $query = $this->objetPDO->prepare('SELECT * FROM `boutique` WHERE idBoutique=?');
                    $query->execute(array($id));
                    $res = $query->fetch();
                }
                if (is_array($res)) {
                    $this->id = $res['idBoutique'];
                    $this->nomBoutique = $res['nomBoutique'];
                    $this->debutAbonnement = $res['debutAbonnement'];
                    $this->finAbonnement = $res['finAbonnement'];
                    $this->imageBanderole = $res['imageBanderole'];
                    $this->imageTampon = $res['imageTampon'];
                    $this->textFooterPDF = $res['textFooterPDF'];
                    $this->date_ajoue_boutique = $res['date_ajoue_boutique'];
                    $this->dateModificationBoutique = $res['dateModificationBoutique'];
                    $this->statutBoutique = $res['statutBoutique'];
                    $this->caisseExiste = $res['caisseExiste'];
                    $this->isDeprecated = $res['isDeprecated'];
                    $this->contact = new Contact($this->objetPDO, $res['id_contact_boutique']);
                    $this->proprietaire = new User($this->objetPDO, $res['id_user']);

                    $this->chercherCaisse();

                    $this->json_array = array(
                        'idBoutique' => $this->id,
                        'nomBoutique' => $this->nomBoutique,
                        'debutAbonnement' => $this->debutAbonnement,
                        'finAbonnement' => $this->finAbonnement,
                        'date_ajoue_boutique' => $this->date_ajoue_boutique,
                        'dateModificationBoutique' => $this->dateModificationBoutique,
                        'statutBoutique' => $this->statutBoutique,
                        'imageBanderole' => $this->imageBanderole,
                        'imageTampon' => $this->imageTampon,
                        'textFooterPDF' => $this->textFooterPDF,
                        'caisseExiste' => $this->caisseExiste,
                        'isDeprecated' => $this->isDeprecated,
                        'contact' => $this->contact->getJson_array(),
                        'proprietaire' => $this->proprietaire->getJson_array(),
                        "idCaisse" => $this->caisse->getId()
                    );

                    $this->existe = true;
                }
            }
        } catch (Exception $e) {
            $this->existe = false;
            echo ($e->getMessage());
        }
    }

    private function chercherCaisse()
    {
        try {
            $caisseTmp = new Caisse($this->objetPDO, null, $this->id);

            if (!$caisseTmp->getExiste()) {
                $query = $this->objetPDO->prepare("INSERT INTO caisse (id_boutique) VALUES(?)");
                $query->execute(array($this->id));

                $caisseTmp = new Caisse($this->objetPDO, null, $this->id);
            }

            $this->caisse = $caisseTmp;
        } catch (Exception $e) {
        }
    }

    public function reconstruct()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM `boutique` WHERE idBoutique=?');
            $query->execute(array($this->id));
            if ($res = $query->fetch()) {
                $this->id = $res['idBoutique'];
                $this->nomBoutique = $res['nomBoutique'];
                $this->debutAbonnement = $res['debutAbonnement'];
                $this->finAbonnement = $res['finAbonnement'];
                $this->textFooterPDF = $res['textFooterPDF'];
                $this->date_ajoue_boutique = $res['date_ajoue_boutique'];
                $this->dateModificationBoutique = $res['dateModificationBoutique'];
                $this->statutBoutique = $res['statutBoutique'];
                $this->contact = new Contact($this->objetPDO, $res['id_contact_boutique']);
                $this->proprietaire = new User($this->objetPDO, $res['id_user']);

                $this->chercherCaisse();

                $this->json_array = array(
                    'idBoutique' => $this->id,
                    'nomBoutique' => $this->nomBoutique,
                    'debutAbonnement' => $this->debutAbonnement,
                    'finAbonnement' => $this->finAbonnement,
                    'textFooterPDF' => $this->textFooterPDF,
                    'date_ajoue_boutique' => $this->date_ajoue_boutique,
                    'dateModificationBoutique' => $this->dateModificationBoutique,
                    'statutBoutique' => $this->statutBoutique,
                    'contact' => $this->contact->getJson_array(),
                    'proprietaire' => $this->proprietaire->getJson_array(),
                    "idCaisse" => $this->caisse->getId()
                );

                $this->chargerProduit();
                $this->chargerFournisseur();
                $this->chargerCommande();

                $this->existe = true;

                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNomBoutique()
    {
        return $this->nomBoutique;
    }
    public function getDebutAbonnement()
    {
        return $this->debutAbonnement;
    }
    public function getFinAbonnement()
    {
        return $this->finAbonnement;
    }
    public function getDate_ajoue_boutique()
    {
        return $this->date_ajoue_boutique;
    }
    public function getdateModificationBoutique()
    {
        return $this->dateModificationBoutique;
    }
    public function getStatutBoutique()
    {
        return $this->statutBoutique;
    }
    public function getImageBanderole()
    {
        return $this->imageBanderole;
    }
    public function getImageTampon()
    {
        return $this->imageTampon;
    }
    public function getTextFooterPDF()
    {
        return $this->textFooterPDF;
    }
    public function getCaisseExiste()
    {
        return $this->caisseExiste;
    }
    public function getContact()
    {
        return $this->contact;
    }
    public function getCaisse()
    {
        return $this->caisse;
    }
    public function getProprietaire()
    {
        return $this->proprietaire;
    }
    public function getIsDeprecated()
    {
        return $this->isDeprecated;
    }
    public function getTableauProduit()
    {
        return $this->tableauProduit;
    }
    public function getTableauFournisseur()
    {
        return $this->tableauFournisseur;
    }
    public function getTableauCommande()
    {
        return $this->tableauCommande;
    }
    public function getTableauDepense()
    {
        return $this->tableauDepense;
    }
    public function getTableauVente()
    {
        return $this->tableauVente;
    }
    public function getTableauVenteJour()
    {
        return $this->tableauVenteJour;
    }
    public function getTableauProformat()
    {
        return $this->tableauProformat;
    }
    public function getTableauAchat()
    {
        return $this->tableauAchat;
    }
    public function getTableauSousTraitence()
    {
        return $this->tableauSousTraitence;
    }
    public function getTableauCarteBancaire()
    {
        return $this->tableauCarteBancaire;
    }


    public function setImage($tampon, $banderole, $textFooterPDF = null)
    {
        try {
            $query = $this->objetPDO->prepare("UPDATE boutique SET imageTampon=? , imageBanderole=?, textFooterPDF=? WHERE idBoutique=?");
            return $query->execute(
                array(
                    is_null($tampon) ? $this->imageTampon : $tampon,
                    is_null($banderole) ? $this->imageBanderole : $banderole,
                    is_null($textFooterPDF) ? $this->textFooterPDF : $textFooterPDF,
                    $this->id
                )
            );
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerProduit()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM produit WHERE id_boutique=? AND statutProduit=0');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Produit.php");
            $tableau_produit_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableau_produit_tmp = new Produit($this->objetPDO, null, $res);
                $this->tableauProduit[] = $tableau_produit_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesproduits' =>  $this->tableauProduit)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerFournisseur()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT idUser FROM user WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            $tableauFournisseur_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauFournisseur_tmp = new User($this->objetPDO, $res['idUser']);
                $this->tableauFournisseur[] = $tableauFournisseur_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesfournisseur' =>  $this->tableauFournisseur)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerCommande()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM commande WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Commande.php");

            $tableauCommande_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauCommande_tmp = new Commande($this->objetPDO, null, $res);
                $this->tableauCommande[] = $tableauCommande_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesCommande' =>  $this->tableauCommande)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerVente()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM vente WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Vente.php");

            $tableauVente_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauVente_tmp = new Vente($this->objetPDO, null, $res);
                $this->tableauVente[] = $tableauVente_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesVentes' =>  $this->tableauVente)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerVenteJour()
    {
        try {
            $currentDate = date('Y-m-d');
            $query = $this->objetPDO->prepare('SELECT * FROM vente WHERE dateVente >= ? AND id_boutique=?');
            $query->execute(array($currentDate, $this->id));
            $existe = false;
            require_once("Vente.php");

            $tableauVenteJour_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $objetTmp = new Vente($this->objetPDO, null, $res);
                $tableauVenteJour_tmp[] = $objetTmp->getJson_array();
                $this->tableauVenteJour[] = $objetTmp;
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesVentesJour' =>  $tableauVenteJour_tmp)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerProformat()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM proformat WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Proformat.php");

            $tableauProformat_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauProformat_tmp = new Proformat($this->objetPDO, null, $res);
                $this->tableauProformat[] = $tableauProformat_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesProformats' =>  $this->tableauProformat)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerAchat()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM achat WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Achat.php");

            $tableauAchat_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauAchat_tmp = new Achat($this->objetPDO, null, $res);
                $this->tableauAchat[] = $tableauAchat_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesAchats' =>  $this->tableauAchat)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerSousTraitence()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM sousTraitence WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("sousTraitence.php");

            $tableauSousTraitence_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauSousTraitence_tmp = new SousTraitence($this->objetPDO, null, $res);
                $this->tableauSousTraitence[] = $tableauSousTraitence_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesSousTraitence' =>  $this->tableauSousTraitence)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerDepense()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM depense WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("Depense.php");

            $tableauDepense_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableauDepense_tmp = new Depense($this->objetPDO, null, $res);
                $this->tableauDepense[] = $tableauDepense_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesDepenses' =>  $this->tableauDepense)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function chargerCarteBancaire()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM carteBancaire WHERE id_boutique=?');
            $query->execute(array($this->id));
            $existe = false;
            require_once("CarteBancaire.php");

            $tableau_tmp = null;
            while ($res = $query->fetch()) {
                $existe = true;
                $tableau_tmp = new CarteBancaire($this->objetPDO, null, null, $res);
                $this->tableauCarteBancaire[] = $tableau_tmp->getJson_array();
            }
            if ($existe) {
                $this->json_array = array_merge(
                    $this->json_array,
                    array('sesCartes' =>  $this->tableauCarteBancaire)
                );
            }

            return $existe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function editInfo($nom, $debutAbonnement, $finAbonnement, $date_ajoue_boutique, $dateModificationBoutique, $statutBoutique, $idProprietaire, $tel, $email, $adresse, $whatsapp)
    {
        $query = $this->objetPDO->prepare('UPDATE boutique SET nomBoutique=? , debutAbonnement=?, finAbonnement=?, date_ajoue_boutique=?, dateModificationBoutique=?, statutBoutique=?, id_user=? WHERE idBoutique=?');

        $res = false;
        $res = $query->execute(array($nom, $debutAbonnement, $finAbonnement, $date_ajoue_boutique, $dateModificationBoutique, $statutBoutique, $idProprietaire, $this->id));

        if ($this->getContact()->getExiste()) {
            $res = $this->getContact()->editInfo($tel, $email, $adresse, $whatsapp);
        } else {
            $query = $this->objetPDO->prepare('INSERT INTO contact (tel , email, adresse, whatsapp) VALUES (?,?,?,?)');
            $query->execute(array($tel, $email, $adresse, $whatsapp));
            $idContact_tmp = $this->objetPDO->lastInsertId();

            $query = $this->objetPDO->prepare('UPDATE boutique SET id_contact_contact=? WHERE idBoutiqe=?');
            $res = $query->execute(array($idContact_tmp, $this->id));
        }

        return $res;
    }

    public function ajouterHistorique($description, $id_user)
    {
        try {
            $query = $this->objetPDO->prepare('INSERT INTO historiqueBoutique (descriptionHistoriqueBoutique, id_boutique, id_user) VALUES (?, ?, ?)');
            return $query->execute(array($description, $this->id, $id_user));
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteUser($idUser)
    {
        $reponse = false;

        $user = new User($this->objetPDO, $idUser);

        if ($user->getExiste()) {
            if ($user->getBoutique() == $this->id) {

                if ($user->getExiste()) {
                    $query = $this->objetPDO->prepare("DELETE FROM user WHERE idUser=?");
                    if ($query->execute(array($user->getId()))) {
                        if ($user->getContact()->getExiste()) {
                            $query = $this->objetPDO->prepare("DELETE FROM contact WHERE idContact=?");
                            $query->execute(array($user->getContact()->getId()));
                        }
                        $reponse = true;
                    }
                }
            }
        }

        return $reponse;
    }

    public function deleteBoutique()
    {
        $query = $this->objetPDO->prepare("DELETE FROM boutique WHERE idBoutique=?");
        return $query->execute(array($this->id));
    }

    public function deleteProduit($id)
    {
        $reponse = false;

        $produit = new Produit($this->objetPDO, $id);

        if ($produit->getExiste()) {
            if ($produit->getIdBoutique() == $this->id) {
                $query = $this->objetPDO->prepare("DELETE FROM produit WHERE idProduit=?");
                $reponse = $query->execute(array($id));
            }
        }

        return $reponse;
    }

}
    // try{

    //     require_once('../script/connexion_bd.php');
    //     $test= new Boutique($bdd, 1);
    //     $test->chargerCommande();
    //     $test->chargerDepense();
    //     $test->showJson();
    // }
    // catch(Exception $e){
    //     echo($e->getMessage());
    // }
