<?php
  ob_start();
  require("../../script/connexion_bd.php");
  require("../../class/User.php");
  require("../../class/Boutique.php");

  if(!isset($_COOKIE['idUser'])){
    header("location:../../index.php");
  }
  if(!isset($_GET['idBoutique'])){
    header('location: ../dashboard/boutique.php');
}
  $userTmp= new User($bdd,$_COOKIE['idUser']);
  if(!$userTmp->getExiste()){
    header('location: ../dashboard/boutique.php');
  }

  if(!in_array($userTmp->getRole()->getRole(),array("admin"))){
    header('location: ../dashboard/boutique.php');
  }
  $idBoutique= $_GET["idBoutique"];
  $boutique = new Boutique($bdd , $idBoutique);
?>
<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Abonnement Boutique</title>
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/Form.css">
    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="title">Abonnement Boutique</div>
        <div class="content">
            <form action="#">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">Nom de la boutique</span>
                        <input type="text" id="nomBoutique" name="login" placeholder="Login de la boutique" disabled value="<?php echo $boutique->getNomBoutique()  ?>">

                    </div>

                    <div class="input-box">
                        <span class="details">login Proprietaire</span>
                        <input type="text" id="loginProprietaire" name="login" placeholder="Login de la boutique" autocomplete="off" required>

                    </div>

                    <div class="input-box">
                        <span class="details">Votre login</span>
                        <input type="text" id="loginAdmin" name="login" placeholder="Entre votre login" autocomplete="off" required>
                        
                    </div>

                    <div class="input-box">
                        <span class="details">Votre mot de passe</span>
                        <input type="password" id="mdpAdmin" name="login" placeholder="Entre votre mot de passe admin" autocomplete="off" required>
                        
                    </div>

                    <div class="input-box">
                        <span class="details">Nombre de mois</span>
                        <input type="number" id="nbreMois" name="login" placeholder="Login de la boutique" min="1" value="1" autocomplete="off" required>
                        <input type="reset" class="reset"> 
                    </div>

                </div>

                <div class="button">
                    <input type="submit" value="Enregistrer" id="btnEnvoyer">
                    <a href="../dashboard/boutique.php" class="annuler">Retour</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/script.js"></script>
      <script src="../../style/js/Form/renouvelerAbonnement.js"></script>
</body>

</html>
