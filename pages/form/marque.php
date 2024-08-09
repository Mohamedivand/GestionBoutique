<?php
ob_start();
// si Personne nest connecter
if (!isset($_COOKIE['idUser'])) {
  header("location:../../index.php");
}

$idUser = $_COOKIE['idUser'];

require("../../script/connexion_bd.php");
require("../../class/User.php");

$user = new User($bdd, $idUser);

// si Vous n'avez pas le droit ou vous n'exister pas
$currentUser = new User($bdd, $_COOKIE['idUser']);
if (!$currentUser->getExiste() || !in_array($currentUser->getRole()->getRole(), array("admin", "proprietaire"))) {
  header("location:../dashboard/boutique.php");
}
?>

<!DOCTYPE html>

<html lang="fr" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Marque</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
  <link rel="stylesheet" href="../../style/css/Form.css">
  <link rel="stylesheet" href="../../style/css/style.css">
  <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

  <script src="../../lib/js/jquery-3.6.1.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <div class="container">
    <div class="title">Nouvelle Marque</div>
    <div class="content">
      <form action="./traitement/ajouter_marque.php" method="POST" enctype="multipart/form-data">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Nom</span>
            <input type="text" id="nom" name="nomMarque" placeholder="Entre le nom de la marque" required>
          </div>

          <div class="input-box">
            <span class="details">Photo</span>
            <input type="file" id="photo" name="files" accept="image/png, image/gif, image/jpeg, image/webp, image/jpg, image/heivc" placeholder="photo...">
          </div>

        </div>

        <div class="button">
          <input type="submit" value="Enregistrer" >
          <a class="annuler" href="../dashboard/infoProduit.php">Retour</a>
        </div>
      </form>
    </div>
  </div>

  <?php
  include("../../include/pop_up.php");
  ?>

  <script src="../../style/js/script.js"></script>
  <script src="../../style/include/pop_up/pop_up.js"></script>
</body>

</html>


<?php
ob_end_flush();
?>