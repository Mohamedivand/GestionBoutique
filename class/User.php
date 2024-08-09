<?php
include_once("ObjectModel.php");
class User extends ObjectModel
{
    private $id;
    private $nom;
    private $prenom;
    private $login;
    private $mdp;
    private $dateAjoutUser;
    private $contact;
    private $isDeprecated;
    private $idBoutique;
    private $role;

    private $tableauBoutique = array();

    public function __construct($bdd, $id = false, $login = false, $mdp = false, $res = null)
    {
        try {
            require_once('Contact.php');
            require_once('Role.php');
            $this->objetPDO = $bdd;

            if (!isset($res)) {
                if ($id) {
                    $query = $this->objetPDO->prepare('SELECT * FROM user, `role` WHERE user.idUser=? AND user.id_role=`role`.idRole');
                    $query->execute(array($id));
                } elseif ($login && $mdp) {
                    $query = $this->objetPDO->prepare('SELECT * FROM user, `role` WHERE user.`login`=? AND user.mdp=? AND user.id_role=`role`.idRole');
                    $query->execute(array($login, $mdp));
                } elseif ($login) {
                    $query = $this->objetPDO->prepare('SELECT * FROM user, `role` WHERE user.`login`=? AND user.id_role=`role`.idRole');
                    $query->execute(array($login));
                }

                $res = $query->fetch();
            }

            if (is_array($res)) {
                $this->id = $res['idUser'];
                $this->nom = $res['nomUser'];
                $this->prenom = $res['prenomUser'];
                if(isset($res['isDeprecated'])){
                    $this->isDeprecated = $res['isDeprecated'];
                }
                $this->login = (isset($res['login'])) ? $res['login'] : null;
                $this->mdp = (isset($res['mdp'])) ? $res['mdp'] : null;
                $this->dateAjoutUser = $res['dateAjoutUser'];

                $this->role = new Role($this->objetPDO, $res['id_role']);

                $this->contact = new Contact($this->objetPDO, $res['id_contact']);

                $this->getAllUserBoutique();

                if (isset($res['id_boutique']) && !empty($res['id_boutique']) && $res['id_boutique'] != '') {
                    $this->idBoutique = $res['id_boutique'];
                }

                $this->json_array = array(
                    'idUser' => $this->id,
                    'nomUser' => $this->nom,
                    'prenomUser' => $this->prenom,
                    'login' => $this->login,
                    'mdp' => $this->mdp,
                    'dateAjoutUser' => $this->dateAjoutUser,
                    'isDeprecated' => $this->isDeprecated,
                    'contact' => $this->contact->getJson_array(),
                    'idBoutique' => $this->idBoutique,
                    'role' => $this->role->getJson_array(),
                    'sesBoutiques' => $this->tableauBoutique
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    private function getAllUserBoutique()
    {
        try {
            if (in_array($this->role->getRole(), array("admin", "proprietaire"))) {
                if ($this->role->getRole() == "admin") {
                    if ($this->isDeprecated == 1) {
                        $query = $this->objetPDO->query('SELECT * FROM boutique');
                    } else {
                        $query = $this->objetPDO->query('SELECT * FROM boutique WHERE isDeprecated != 1');
                    }
                } elseif ($this->role->getRole() == "proprietaire") {
                    $query = $this->objetPDO->prepare('SELECT * FROM boutique WHERE id_user=?');
                    $query->execute(array($this->id));
                }
                $existe = false;
                while ($res = $query->fetch()) {
                    $existe = true;
                    $contact_tmp = new Contact($this->objetPDO, $res['id_contact_boutique']);

                    $this->tableauBoutique[] = array(
                        'idBoutique' => $res['idBoutique'],
                        'nomBoutique' => $res['nomBoutique'],
                        'debutAbonnement' => $res['debutAbonnement'],
                        'finAbonnement' => $res['finAbonnement'],
                        'date_ajoue_boutique' => $res['date_ajoue_boutique'],
                        'dateModificationBoutique' => $res['dateModificationBoutique'],
                        'statutBoutique' => $res['statutBoutique'],
                        'contact' => $contact_tmp->getJson_array(),
                    );
                }

                if ($existe) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    private function reconstruct()
    {
        try {
            require_once('Contact.php');
            require_once('Role.php');
            // require_once('Boutique.php');

            $query = $this->objetPDO->prepare('SELECT * FROM user, `role` WHERE user.idUser=? AND user.id_role=`role`.idRole');
            $query->execute(array($this->id));

            if ($res = $query->fetch()) {
                $this->id = $res['idUser'];
                $this->nom = $res['nomUser'];
                $this->prenom = $res['prenomUser'];
                $this->isDeprecated = $res['isDeprecated'];
                $this->login = (isset($res['login'])) ? $res['login'] : null;
                $this->mdp = (isset($res['mdp'])) ? $res['mdp'] : null;
                $this->dateAjoutUser = $res['dateAjoutUser'];

                if (isset($res['id_role']) && !empty($res['id_role']) && $res['id_role'] != '') {
                    $this->role = new Role($this->objetPDO, $res['id_role']);
                }

                if (isset($res['id_contact']) && !empty($res['id_contact']) && $res['id_contact'] != '') {
                    $this->contact = new Contact($this->objetPDO, $res['id_contact']);
                }
                if (isset($res['id_boutique']) && !empty($res['id_boutique']) && $res['id_boutique'] != '') {
                    $this->idBoutique = $res['id_boutique'];
                }

                $this->json_array = array(
                    'idUser' => $this->id,
                    'nomUser' => $this->nom,
                    'prenomUser' => $this->prenom,
                    'login' => $this->login,
                    'mdp' => $this->mdp,
                    'dateAjoutUser' => $this->dateAjoutUser,
                    'isDeprecated' => $this->isDeprecated,
                    'contact' => $this->contact->getJson_array(),
                    'idBoutique' => $this->idBoutique,
                    'role' => $this->role->getJson_array(),
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
    public function getRole()
    {
        return $this->role;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getlogin()
    {
        return $this->login;
    }
    public function getMdp()
    {
        return $this->mdp;
    }
    public function getDateAjoutUser()
    {
        return $this->dateAjoutUser;
    }
    public function getContact()
    {
        return $this->contact;
    }
    public function getIsDeprecated()
    {
        return $this->isDeprecated;
    }
    public function getBoutique()
    {
        return $this->idBoutique;
    }
    public function getTableauBoutique()
    {
        return $this->tableauBoutique;
    }

    public function setTel($val)
    {
        try {
            if (!isset($this->contact)) {
                $query = $this->objetPDO->prepare("INSERT INTO contact (tel) VALUES (?)");
                if (!$query->execute([
                    $val
                ])) {
                    return false;
                }

                $id_contact = $this->objetPDO->lastInsertId();

                $query = $this->objetPDO->prepare("UPDATE user SET id_contact=? WHERE idUser=?");
                return ($query->execute([
                    $id_contact,
                    $this->id
                ]));
            } else {
                return $this->contact->editInfo(
                    $val,
                    $this->contact->getEmail(),
                    $this->contact->getAdresse(),
                    $this->contact->getWhatsapp()
                );
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function editInfo($nom, $prenom, $login, $mdp, $dateAjout, $idBoutique, $idRole, $tel, $email, $adresse, $whatsapp)
    {
        $query = $this->objetPDO->prepare('UPDATE user SET nomUser=? , prenomUser=?, `login`=?, mdp=?, dateAjoutUser=?, id_boutique=?, id_role=? WHERE idUser=?');

        $res = false;
        $res = $query->execute(array($nom, $prenom, $login, $mdp, $dateAjout, $idBoutique, $idRole, $this->id));

        if ($this->getContact()->getExiste()) {
            $res = $this->getContact()->editInfo($tel, $email, $adresse, $whatsapp);
        } else {
            $query = $this->objetPDO->prepare('INSERT INTO contact (tel , email, adresse, whatsapp) VALUES (?,?,?,?)');
            $query->execute(array($tel, $email, $adresse, $whatsapp));
            $idContact_tmp = $this->objetPDO->lastInsertId();

            $query = $this->objetPDO->prepare('UPDATE user SET id_contact=? WHERE idUser=?');
            $res = $query->execute(array($idContact_tmp, $this->id));
        }

        if ($res) {
            $this->reconstruct();
        }

        return $res;
    }

    public function ajouterHistorique($description)
    {
        try {
            $query = $this->objetPDO->prepare('INSERT INTO historiqueUser (descriptionHistoriqueUser, id_user) VALUES (?, ?)');
            ($query->execute(array($description, $this->id))) ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }
}

    // try{
    //     require('../script/connexion_bd.php');
    //     $test= new User($bdd, 1);
    //     echo(json_encode($test->getJson_array()));
    // }
    // catch(Exception $e){
    //     echo($e->getMessage());
    // }
    
    // ob_end_flush();
