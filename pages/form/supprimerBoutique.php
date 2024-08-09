<?php
  ob_start();
  require("../../script/connexion_bd.php");
  require("../../class/User.php");
  require("../../class/Boutique.php");

  if(!isset($_COOKIE['idUser'])){
    header("location:../../index.php");
  }
  $userTmp= new User($bdd,$_COOKIE['idUser']);
  if(!$userTmp->getExiste()){
    header("location:../../index.php");
  }

  if(!in_array($userTmp->getRole()->getRole(),array("admin"))){
    if(!isset($_GET['idBoutique'])){
        header('Location : ../../index.php');
    }
  }
  $idBoutique= $_GET['idBoutique'];
  $boutique = new Boutique($bdd , $idBoutique);
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Suppession d'une boutique</title>
  <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
  <link rel="stylesheet" href="../../style/css/style.css">
  <link rel="stylesheet" href="../../style/css/Form.css">
  <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

  <script src="../../lib/js/jquery-3.6.1.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <div class="container">
    <div class="title">Suppression Boutique</div>
    <div class="content">
      <form action="" data-idBoutique="<?php echo ($idBoutique) ?>" class="form-inline">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Nom de la boutique</span>
            <input type="text" id="nomBoutique" disabled  placeholder="nom de la boutique a supprimer" value="<?php echo $boutique->getNomBoutique()  ?>">
          </div>

          <div class="input-box">
            <span class="details">Login du proprietaire</span>
            <input type="text" id="loginProprietaire"  placeholder="login de la boutique a supprimer" value="">
          </div>

          <div class="input-box">
            <span class="details">Votre login</span>
            <input type="text" id="loginAdmin" name="loginUser" placeholder="Entre votre login" required>
          </div>

          <div class="input-box">
            <span class="details">Votre mot de passe</span>
            <input type="password" id="mdpAdmin" name="passwordUser" placeholder="Entre votre mot de passe" required>
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
  <script src="../../style/js/Form/supprimerBoutique.js"></script>
  <script src="../../style/include/pop_up/pop_up.js"></script>
</body>
</html>



<?php
  ob_end_flush();
?>