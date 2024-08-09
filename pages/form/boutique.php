<?php
ob_start();
require("../../script/connexion_bd.php");
require("../../class/User.php");
require("../../class/Boutique.php");

if (!isset($_COOKIE['idUser'])) {
  header("location:../../index.php");
}
$userTmp = new User($bdd, $_COOKIE['idUser']);
if (!$userTmp->getExiste() || !in_array($userTmp->getRole()->getRole(), array("admin"))) {
  header("location:../../index.php");
}

$idBoutique = isset($_GET['idBoutique']) ? $_GET['idBoutique'] : null;

$boutique = new Boutique($bdd, $idBoutique);

if (isset($_GET['idBoutique']) && !$boutique->getExiste()) {
  header("location:../dashboard/boutique.php");
}

?>




<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Boutique</title>
  <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
  <link rel="stylesheet" href="../../style/css/style.css">
  <link rel="stylesheet" href="../../style/css/Form.css">
  <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

  <script src="../../lib/js/jquery-3.6.1.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <div class="container">
    <div class="title">Nouvelle Boutique</div>
    <div class="content">
      <form action="" data-idBoutique="<?php echo ($idBoutique) ?>" class="form-inline">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Nom de la boutique</span>
            <input type="text" id="nomBoutique" name="nom" placeholder="Nom de la boutique" value="<?php echo ($boutique->getExiste() ? $boutique->getNomBoutique() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Téléphone</span>
            <input type="number" id="telBoutique" name="tel" placeholder="Entre le numero" value="<?php echo ($boutique->getExiste() ? $boutique->getContact()->getTel() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Whatsapp</span>
            <input type="number" min="50000000" max="99999999" id="whatsappBoutique" name="whatsapp" placeholder="Numero whatsapp" value="<?php echo ($boutique->getExiste() ? $boutique->getContact()->getWhatsapp() : null) ?>">
          </div>

          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" id="emailBoutique" name="email" placeholder="Entre l'email" value="<?php echo ($boutique->getExiste() ? $boutique->getContact()->getEmail() : null) ?>">
          </div>

          <div class="input-box">
            <span class="details">Adresse</span>
            <textarea id="adresseBoutique" placeholder="Entre l'adresse" cols="30" rows="10" <?php echo ($boutique->getExiste() ? $boutique->getContact()->getAdresse() : null) ?>></textarea>
          </div>

          <div class="input-box">
            <span class="details">Proprietaire</span>
            <select id="proprietaire" name="proprietaire" required>
              <?php
              $query = $bdd->prepare('SELECT idUser FROM `user`, `role` WHERE user.id_role=role.idRole AND `role`.nomRole=? ORDER BY user.nomUser');
              $query->execute(array('proprietaire'));
              while ($res = $query->fetch()) {
                $user = new User($bdd, $res['idUser']);
                $selected = "";
                if ($boutique->getExiste()) {
                  if ($boutique->getProprietaire()->getId() == $user->getId()) {
                    $selected = "selected";
                  }
                }
              ?>
                <option value="<?php echo ($user->getId()) ?>" <?php echo ($selected) ?>>
                  <?php
                  echo ($user->getNom());
                  if ($user->getContact() != null) {
                    if ($user->getContact()->getExiste() && $user->getContact()->getTel()) {
                      echo (" => " . $user->getContact()->getTel());
                    }
                  }

                  ?>
                </option>
              <?php
              }
              ?>

            </select>
            </select>
          </div>

        </div>

        <div class="button">
          <input type="submit" value="Enregistrer" id="btnEnvoyer">
          <a class="annuler" href="../dashboard/boutique.php">Retour</a>
        </div>
      </form>
    </div>
  </div>

  <?php
  include("../../include/pop_up.php");
  ?>

  <script src="../../style/js/script.js"></script>
  <script src="../../style/js/Form/boutique.js"></script>
  <script src="../../style/include/pop_up/pop_up.js"></script>
</body>
</html>