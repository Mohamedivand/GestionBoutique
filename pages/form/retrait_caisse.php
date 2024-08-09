<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>retrait Bancaire</title>
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/Form.css">
    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="title">Nouveau retrait</div>
        <div class="content">
            <form action="#">
                <div class="user-details">

                <div class="input-box">
                        <span class="details">date</span>
                        <input type="date" placeholder="Nom de l'employer" id="dateRetrait" required>
                    </div> 
                    <div class="input-box">
                        <span class="details" >Nom Employer</span>
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
                    <div class="input-box motifBox">
                        <span class="details">Motif</span>
                        <input type="text" placeholder="Entrer le motif de votre retrait" id="motif" required>
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
    <script src="../../style/js/Form/retraitCaisse.js"></script>
</body>

</html>