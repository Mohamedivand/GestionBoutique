<?php
include_once("ObjectModel.php");
class Contact extends ObjectModel
{
    private $id;
    private $tel;
    private $email;
    private $adresse;
    private $whatsapp;

    public function __construct($bdd, $id)
    {
        $this->objetPDO = $bdd;
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM contact WHERE idContact=?');
            $query->execute(array($id));
            if ($res = $query->fetch()) {
                $this->id = $res['idContact'];
                $this->tel = $res['tel'];
                $this->email = $res['email'];
                $this->adresse = $res['adresse'];
                $this->whatsapp = $res['whatsapp'];

                $this->json_array = array(
                    "idContact" => $this->id,
                    "tel" => $this->tel,
                    "email" => $this->email,
                    "adresse" => $this->adresse,
                    "whatsapp" => $this->whatsapp,
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    private function reconstruct()
    {
        try {
            $query = $this->objetPDO->prepare('SELECT * FROM contact WHERE idContact=?');
            $query->execute(array($this->id));
            if ($res = $query->fetch()) {
                $this->id = $res['idContact'];
                $this->tel = $res['tel'];
                $this->email = $res['email'];
                $this->adresse = $res['adresse'];
                $this->whatsapp = $res['whatsapp'];

                $this->json_array = array(
                    "idContact" => $this->id,
                    "tel" => $this->tel,
                    "email" => $this->email,
                    "adresse" => $this->adresse,
                    "whatsapp" => $this->whatsapp,
                );

                $this->existe = true;
            }
        } catch (Exception $e) {
            $this->existe = false;
        }
    }

    public function getExiste()
    {
        return $this->existe;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($value)
    {
        $this->id = $value;
    }
    public function getTel()
    {
        return $this->tel;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function getWhatsapp()
    {
        return $this->whatsapp;
    }

    public function editInfo($tel, $email, $adresse, $whatsapp)
    {
        $query = $this->objetPDO->prepare('UPDATE contact SET tel=? , email=?, `adresse`=?, whatsapp=? WHERE idContact=?');

        if ($query->execute(array($tel, $email, $adresse, $whatsapp, $this->id))) {
            $this->reconstruct();
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = $this->objetPDO->prepare('DELETE FROM contact WHERE idContact=?');

        if ($query->execute(array($this->id))) {
            $this->reconstruct();
            return true;
        } else {
            return false;
        }
    }
}

// require('../script/connexion_bd.php');
// $test= new Contact($bdd, 1);

// $test->editInfo("75", "705", "45", "45");
// $test->showJson();
// ob_end_flush();
