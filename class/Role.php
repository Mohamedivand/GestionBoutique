<?php
include_once("ObjectModel.php");
class Role extends ObjectModel
{
    private $id;
    private $role;

    public function __construct($bdd, $id, $res = null)
    {
        $this->objetPDO = $bdd;
        try {
            if (!isset($res)) {
                $query = $this->objetPDO->prepare('SELECT * FROM `role` WHERE idRole=?');
                $query->execute(array($id));
                $res = $query->fetch();
            }

            if (is_array($res)) {
                $this->id = $res['idRole'];
                $this->role = $res['nomRole'];

                $this->json_array = array(
                    'idRole' => $this->id,
                    'nomRole' => $this->role,
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
    public function getRole()
    {
        return $this->role;
    }
    public function setRole($value)
    {
        $this->role = $value;
    }
}

// require('../script/connexion_bd.php');
// $test= new Role($bdd, 1);

// $test->showJson();
// ob_end_flush();
