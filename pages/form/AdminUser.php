<?php
require_once("../../script/connexion_bd.php");
require_once("../../class/User.php");
require_once("../../class/Boutique.php");
require_once("../../class/Role.php");

// si Personne nest connecter
if (!isset($_COOKIE['idUser'])) {
  header("location:../../index.php");
}

// si Vous n'avez pas le droit ou vous n'exister pas
$currentUser = new User($bdd, $_COOKIE['idUser']);
if (!$currentUser->getExiste() || !in_array($currentUser->getRole()->getRole(), array("admin"))) {
  header("location:../../index.php");
}
// on cree utilisateur qui nexiste pas
$user = new User($bdd, "djessy");

// on verifie si lutilisateur recu existe ou est dans une Boutique
if (isset($_GET['idUser'])) {
  $idUser = $_GET['idUser'];
  $user = new User($bdd, $idUser);
  if (!$user->getExiste()) {
    header("location:../dashboard/boutique.php");
  }
}

?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Utilisateur</title>
  <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
  <link rel="stylesheet" href="../../style/css/style.css">
  <link rel="stylesheet" href="../../style/css/Form.css">
  <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

  <script src="../../lib/js/jquery-3.6.1.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <div class="container">
    <div class="title">Gestion d'utilisateur</div>
    <div class="content">
      <form action="">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Nom</span>
            <input type="text" id="nomUser" placeholder="Entre le nom" value="<?php echo ($user->getExiste() ? $user->getNom() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Prénom</span>
            <input type="text" id="prenomUser" placeholder="Entre le nom prénom" value="<?php echo ($user->getExiste() ? $user->getPrenom() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Login</span>
            <input type="text" id="login" placeholder="Entre le login" value="<?php echo ($user->getExiste() ? $user->getlogin() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Mot de passe</span>
            <input type="text" id="mdp" placeholder="Entre le mot de passe" value="<?php echo ($user->getExiste() ? $user->getMdp() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Téléphone</span>
            <input type="num" id="tel" placeholder="Entre le numero" value="<?php echo ($user->getExiste() ? $user->getContact()->getTel() : null) ?>" required>
          </div>

          <div class="input-box">
            <span class="details">Whatsapp</span>
            <input type="num" id="whatsapp" placeholder="Entre le numero whatsapp" value="<?php echo ($user->getExiste() ? $user->getContact()->getWhatsapp() : null) ?>">
          </div>

          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" id="email" placeholder="Email..." value="<?php echo ($user->getExiste() ? $user->getContact()->getEmail() : null) ?>">
          </div>

          <div class="input-box">
            <span class="details">Adresse</span>
            <textarea id="adresse" placeholder="Entre l'adresse" cols="30" rows="10"><?php echo ($user->getExiste() ? $user->getContact()->getAdresse() : null) ?></textarea>
          </div>

          <div class="input-box">
            <span class="details">Rôle</span>
            <select id="role" required>
              <?php
              // On recupere les roles
              if (in_array($currentUser->getRole()->getRole(), array("proprietaire"))) {
                $query = $bdd->prepare("SELECT * FROM `role` WHERE `nomRole` != ? AND `nomRole` != ? ORDER BY `nomRole`");
                $query->execute(array("admin", "proprietaire"));
              } else {
                $query = $bdd->prepare("SELECT * FROM `role` WHERE `nomRole` != ? AND `nomRole` != ? ORDER BY `nomRole`");
                $query->execute(array("client", "fournisseur"));
              }

              while ($res = $query->fetch()) {
                $role = new Role($bdd, null, $res);
                // on recupere de le role de lutilisateur selectionner
                if ($user->getExiste()) {
                  if ($user->getRole()->getId() == $role->getId()) {
              ?>
                    <option value="<?php echo ($user->getRole()->getId()) ?>" selected>
                      <?php echo ($user->getRole()->getRole()) ?>
                    </option>
                <?php
                    continue;
                  }
                }
                ?>
                <option value="<?php echo ($role->getId()) ?>">
                  <?php echo ($role->getRole()) ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>

        </div>

        <div class="button">
          <input type="submit" value="Enregistrer" id="btnEnvoyer">
          <a class="annuler" href="../dashboard/AdminUser.php">Retour</a>
        </div>
      </form>
    </div>
  </div>

  <?php
  include("../../include/pop_up.php");
  ?>

  <script src="../../style/js/script.js"></script>
  <script src="../../style/js/Form/addUser.js"></script>
  <script src="../../style/include/pop_up/pop_up.js"></script>
</body>

</html>