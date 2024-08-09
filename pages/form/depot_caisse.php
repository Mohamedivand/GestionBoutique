<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Depot Bancaire</title>
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/Form.css">
    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="title">Nouveau Depot</div>
        <div class="content">
            <form action="#">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">date</span>
                        <input type="date" placeholder="Nom de l'employer" id="dateDepot" required>
                    </div> 
                    <div class="input-box">
                        <span class="details">Nom Employer</span>
                        <input type="text" placeholder="Nom de l'employer" id="nomEmployer" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Numero Employer</span>
                        <input type="text" placeholder="Numero de l'employer" id="numEmployer" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Montant</span>
                        <input type="number" placeholder="Entrer le montant a deposer" min="1" id="montant" required>
                    </div>
           
                </div>
                <div class="button">
                    <input type="submit" value="Enregistrer" id="btnEnvoyer">
                    <a href="../dashboard/caisse.php" class="annuler">Retour</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/Form/depotCaisse.js"></script>
</body>

</html>