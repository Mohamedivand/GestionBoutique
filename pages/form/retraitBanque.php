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
        <div class="title">Nouveau Retrait</div>
        <div class="content">
            <form action="#">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">Date</span>
                        <input type="date" placeholder="Nom de l'employer" id="date" required>
                    </div> 
                    <div class="input-box">
                        <span class="details">Montant</span>
                        <input type="number" placeholder="Entrer le montant  " id="montantRetrait" min="1" required>
                    </div> 
                    <div class="input-box">
                        <span class="details">Numero compte</span>
                        <input type="num"  id="numCarte" required disabled>
                    </div> 
                    <div class="input-box">
                        <span class="details">Nom Employer</span>
                        <input type="text" placeholder="Nom de l'employer" id="nomEmployer" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Numero Employer</span>
                        <input type="num" placeholder="Numero de l'employer" id="numEmployer" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Motif</span>
                        <input type="num" placeholder="Numero de l'employer" id="motif" required>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" value="Enregistrer" id="btnRetrait">
                    <a href="../dashboard/banque.php" class="annuler">Retour</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/Form/banque.js"></script>
</body>

</html>