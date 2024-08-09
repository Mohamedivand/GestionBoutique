<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Ajout de Type</title>
    <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/Form.css">
    <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  
    <div class="container">
        <div class="title">Nouvelle Type</div>
        <div class="content">
        <form action="">
                <div class="user-details">

                   <div class="input-box">
                    <span class="details">Nom</span>
                    <input type="text" id="nomType" name="nom" placeholder="Nom du type" required>
                  </div>

                    <div class="input-box">
                      <span class="details">Description</span>
                      <textarea id="descType" name="description" placeholder="Une description"  cols="30" rows="10" required></textarea>
                    </div>

                </div>

                <div class="button">
                    <input type="submit" value="Enregistrer" id="btnEnvoyer">
                    <a class="annuler" href="../dashboard/infoProduit.php">Retour</a>
                </div>
            </form>
        </div>
    </div>

    <?php
    include("../../include/pop_up.php");
    ?>

    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/Form/type.js"></script>
    <script src="../../style/include/pop_up/pop_up.js"></script>
</body>
</html>